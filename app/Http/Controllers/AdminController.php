<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Invoice;
use App\Models\Resume;
use App\Models\User;
use App\Support\ConverterCatalog;
use App\Support\ResumeCatalog;
use App\Support\TemplateCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Unified Admin Dashboard showing Blogs (Add/Edit/Active/Inactive/Delete)
     * plus all Website Modules data (Invoices, Business Docs, Resumes, Users, Tools & SEO).
     */
    public function dashboard(Request $request): View
    {
        $activeTab = (string) $request->query('tab', 'overview');
        $blogStatus = (string) $request->query('blog_status', 'all');
        $blogSearch = trim((string) $request->query('blog_q', ''));
        $invoiceSearch = trim((string) $request->query('inv_q', ''));

        // KPI Metrics across every website module
        $stats = [
            'users_total' => User::count(),
            'users_admin' => User::where('is_admin', true)->count(),
            'blogs_total' => BlogPost::count(),
            'blogs_active' => BlogPost::where('is_published', true)->count(),
            'blogs_inactive' => BlogPost::where('is_published', false)->count(),
            'invoices_total' => Invoice::count(),
            'invoices_paid' => Invoice::where('status', 'paid')->count(),
            'invoices_pending' => Invoice::where('status', '!=', 'paid')->count(),
            'resumes_total' => Resume::count(),
            'generators_count' => count(InvoiceController::$generators),
            'converters_count' => count(ConverterCatalog::all()),
            'invoice_templates_count' => count(TemplateCatalog::templates()),
            'resume_templates_count' => count(ResumeCatalog::templates()),
        ];

        // Blogs filtered query
        $blogsQuery = BlogPost::with('author:id,name')->orderByDesc('updated_at');
        if ($blogStatus === 'active') {
            $blogsQuery->where('is_published', true);
        } elseif ($blogStatus === 'inactive') {
            $blogsQuery->where('is_published', false);
        }
        if ($blogSearch !== '') {
            $blogsQuery->where(function ($q) use ($blogSearch) {
                $q->where('title', 'like', '%' . $blogSearch . '%')
                    ->orWhere('slug', 'like', '%' . $blogSearch . '%')
                    ->orWhere('focus_keyword', 'like', '%' . $blogSearch . '%');
            });
        }
        $blogs = $blogsQuery->take(50)->get();

        // Invoices & Business Docs data
        $invoicesQuery = Invoice::with('user:id,name,email')->orderByDesc('created_at');
        if ($invoiceSearch !== '') {
            $invoicesQuery->where(function ($q) use ($invoiceSearch) {
                $q->where('invoice_number', 'like', '%' . $invoiceSearch . '%')
                    ->orWhere('invoice_title', 'like', '%' . $invoiceSearch . '%')
                    ->orWhere('from_name', 'like', '%' . $invoiceSearch . '%')
                    ->orWhere('to_name', 'like', '%' . $invoiceSearch . '%');
            });
        }
        $invoices = $invoicesQuery->take(40)->get();

        $docTypeBreakdown = Invoice::selectRaw('invoice_title, COUNT(*) as total')
            ->groupBy('invoice_title')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        // Resumes data
        $resumes = Resume::with('user:id,name,email')
            ->orderByDesc('created_at')
            ->take(40)
            ->get();

        $resumeStyleBreakdown = Resume::selectRaw('template_style, COUNT(*) as total')
            ->groupBy('template_style')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // Users data
        $users = User::withCount(['invoices', 'resumes', 'blogPosts'])
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        // Sitemap inspection
        $sitemapPath = public_path('sitemap.xml');
        $sitemapMeta = [
            'exists' => file_exists($sitemapPath),
            'updated_at' => file_exists($sitemapPath) ? date('M j, Y H:i', filemtime($sitemapPath)) : null,
            'urls_count' => file_exists($sitemapPath)
                ? substr_count((string) file_get_contents($sitemapPath), '<url>')
                : 0,
        ];

        return view('admin.dashboard', [
            'activeTab' => $activeTab,
            'blogStatus' => $blogStatus,
            'blogSearch' => $blogSearch,
            'invoiceSearch' => $invoiceSearch,
            'stats' => $stats,
            'blogs' => $blogs,
            'blogCategories' => BlogPost::categories(),
            'invoices' => $invoices,
            'docTypeBreakdown' => $docTypeBreakdown,
            'resumes' => $resumes,
            'resumeStyleBreakdown' => $resumeStyleBreakdown,
            'resumeStyles' => ResumeCatalog::styles(),
            'users' => $users,
            'generators' => InvoiceController::$generators,
            'converterTools' => ConverterCatalog::all(),
            'sitemapMeta' => $sitemapMeta,
            'seo' => ['robots' => 'noindex,nofollow', 'title' => 'Admin Dashboard | Formnexa'],
        ]);
    }

    /**
     * Show Create Blog Form inside Admin.
     */
    public function createBlog(): View
    {
        return view('blog.form', [
            'post' => new BlogPost([
                'category' => 'invoicing',
                'is_published' => true,
                'reading_time_minutes' => 4,
            ]),
            'categories' => BlogPost::categories(),
            'toolOptions' => $this->toolOptions(),
            'isEdit' => false,
            'formAction' => route('admin.blogs.store'),
            'backUrl' => route('admin.dashboard', ['tab' => 'blogs']),
            'backLabel' => 'Back to Admin Blogs',
            'seo' => ['robots' => 'noindex,nofollow', 'title' => 'New Blog Article — Admin | Formnexa'],
        ]);
    }

    /**
     * Store New Blog Article from Admin.
     */
    public function storeBlog(Request $request): RedirectResponse
    {
        $data = $this->validatedBlogPayload($request);
        $data['user_id'] = Auth::id();

        $post = BlogPost::create($data);
        $this->refreshSitemapSafely();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'blogs'])
            ->with('success', 'Blog "' . $post->title . '" created (' . ($post->is_published ? 'Active' : 'Inactive') . ') and sitemap refreshed.');
    }

    /**
     * Show Edit Blog Form inside Admin.
     */
    public function editBlog(BlogPost $post): View
    {
        return view('blog.form', [
            'post' => $post,
            'categories' => BlogPost::categories(),
            'toolOptions' => $this->toolOptions(),
            'isEdit' => true,
            'formAction' => route('admin.blogs.update', $post),
            'backUrl' => route('admin.dashboard', ['tab' => 'blogs']),
            'backLabel' => 'Back to Admin Blogs',
            'seo' => ['robots' => 'noindex,nofollow', 'title' => 'Edit Blog — Admin | Formnexa'],
        ]);
    }

    /**
     * Update Blog Article from Admin.
     */
    public function updateBlog(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $this->validatedBlogPayload($request, $post->id);
        $post->update($data);
        $this->refreshSitemapSafely();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'blogs'])
            ->with('success', 'Blog "' . $post->title . '" updated and sitemap refreshed.');
    }

    /**
     * Quick Active / Inactive Toggle for Blog Post.
     */
    public function toggleBlogStatus(BlogPost $post): RedirectResponse
    {
        $nextState = !$post->is_published;
        $post->update([
            'is_published' => $nextState,
            'published_at' => $nextState ? ($post->published_at ?? now()) : $post->published_at,
        ]);
        $this->refreshSitemapSafely();

        $label = $nextState ? 'Active (Published)' : 'Inactive (Hidden from public)';

        return redirect()
            ->route('admin.dashboard', ['tab' => 'blogs'])
            ->with('success', 'Blog "' . $post->title . '" marked ' . $label . '.');
    }

    /**
     * Delete Blog Post from Admin.
     */
    public function destroyBlog(BlogPost $post): RedirectResponse
    {
        $title = $post->title;
        $post->delete();
        $this->refreshSitemapSafely();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'blogs'])
            ->with('success', 'Blog "' . $title . '" deleted and removed from sitemap.');
    }

    /**
     * Toggle Invoice / Document Status (Paid <-> Draft).
     */
    public function toggleInvoiceStatus(Invoice $invoice): RedirectResponse
    {
        $next = $invoice->status === 'paid' ? 'draft' : 'paid';
        $invoice->update([
            'status' => $next,
            'balance_due' => $next === 'paid' ? 0 : $invoice->total,
        ]);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'invoices'])
            ->with('success', 'Document #' . $invoice->invoice_number . ' updated to ' . strtoupper($next) . '.');
    }

    /**
     * Delete Invoice / Document from Admin.
     */
    public function destroyInvoice(Invoice $invoice): RedirectResponse
    {
        $num = $invoice->invoice_number;
        $invoice->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'invoices'])
            ->with('success', 'Document #' . $num . ' deleted.');
    }

    /**
     * Delete Resume from Admin.
     */
    public function destroyResume(Resume $resume): RedirectResponse
    {
        $title = $resume->title;
        $resume->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'resumes'])
            ->with('success', 'Resume "' . $title . '" deleted.');
    }

    /**
     * Toggle Admin Role for a User (guards against self-demotion).
     */
    public function toggleUserAdmin(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.dashboard', ['tab' => 'users'])
                ->with('error', 'You cannot remove your own administrator permission while signed in.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'users'])
            ->with('success', $user->name . ' admin access ' . ($user->is_admin ? 'enabled.' : 'revoked.'));
    }

    /**
     * Manually rebuild public/sitemap.xml from Admin SEO panel.
     */
    public function regenerateSitemap(): RedirectResponse
    {
        Artisan::call('seo:sitemap');

        return redirect()
            ->route('admin.dashboard', ['tab' => 'seo'])
            ->with('success', trim(Artisan::output()) ?: 'Sitemap regenerated successfully.');
    }

    protected function validatedBlogPayload(Request $request, ?int $ignoreId = null): array
    {
        $categories = BlogPost::categories();

        $rawSlug = trim((string) $request->input('slug', ''));
        $slugCandidate = Str::slug($rawSlug !== '' ? $rawSlug : (string) $request->input('title', ''));
        if ($slugCandidate === '') {
            $slugCandidate = 'article-' . Str::lower(Str::random(6));
        }
        $request->merge(['slug' => $slugCandidate]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:6', 'max:200'],
            'slug' => ['required', 'string', 'max:200', Rule::unique('blog_posts', 'slug')->ignore($ignoreId)],
            'category' => ['required', 'string', Rule::in(array_keys($categories))],
            'excerpt' => ['required', 'string', 'min:20', 'max:450'],
            'content' => ['required', 'string', 'min:50'],
            'featured_tool_route' => ['nullable', 'string', 'max:100'],
            'featured_tool_label' => ['nullable', 'string', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'focus_keyword' => ['nullable', 'string', 'max:120'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $isPublished = $request->boolean('is_published', false);
        $wordCount = str_word_count(strip_tags($validated['content']));

        return [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category' => $validated['category'],
            'category_label' => $categories[$validated['category']] ?? 'Guides',
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_tool_route' => ($validated['featured_tool_route'] ?? null) ?: null,
            'featured_tool_label' => ($validated['featured_tool_label'] ?? null) ?: null,
            'meta_title' => ($validated['meta_title'] ?? null) ?: null,
            'meta_description' => ($validated['meta_description'] ?? null) ?: null,
            'focus_keyword' => ($validated['focus_keyword'] ?? null) ?: null,
            'reading_time_minutes' => max(1, (int) ceil($wordCount / 200)),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function toolOptions(): array
    {
        $options = [
            'invoice.studio' => 'Invoice Generator (/invoice-generator)',
            'resume.builder' => 'ATS Resume Builder (/resume-builder)',
            'resume.templates' => 'Resume Templates (/resume-templates)',
            'templates.index' => 'Invoice Templates (/templates)',
            'convert.hub' => 'PDF Converter Hub (/convert)',
        ];

        foreach (InvoiceController::$generators as $slug => $meta) {
            if ($slug === 'invoice-generator') {
                continue;
            }
            $options['invoice.generator:' . $slug] = $meta['name'] . ' (/' . $slug . ')';
        }

        foreach (ConverterCatalog::all() as $tool) {
            $options['convert.tool:' . $tool['slug']] = $tool['name'] . ' (/convert/' . $tool['slug'] . ')';
        }

        return $options;
    }

    protected function refreshSitemapSafely(): void
    {
        try {
            Artisan::call('seo:sitemap');
        } catch (\Throwable) {
            // Non-blocking
        }
    }
}
