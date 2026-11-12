<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Support\ConverterCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Public Blog Directory (/blog)
     */
    public function index(Request $request): View
    {
        $categories = BlogPost::categories();
        $search = trim((string) $request->query('q', ''));

        $baseQuery = BlogPost::published()->orderByDesc('published_at')->orderByDesc('id');

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('focus_keyword', 'like', '%' . $search . '%');
            });
        }

        $posts = $baseQuery->paginate(9)->withQueryString();
        $featuredPost = $search === '' && $posts->currentPage() === 1
            ? $posts->first()
            : null;

        $categoryCounts = BlogPost::published()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->all();

        $seo = [
            'canonical' => route('blog.index'),
        ];

        if ($search !== '') {
            $seo['robots'] = 'noindex,follow';
            $seo['title'] = 'Search Guides: ' . Str::limit($search, 30) . ' | Formnexa';
        } elseif ($posts->currentPage() > 1) {
            $seo['canonical'] = $posts->url($posts->currentPage());
            $seo['title'] = 'Blog Guides – Page ' . $posts->currentPage() . ' | Formnexa';
        }

        return view('blog.index', [
            'posts' => $posts,
            'featuredPost' => $featuredPost,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'activeCategory' => null,
            'search' => $search,
            'seo' => $seo,
        ]);
    }

    /**
     * Public Blog Category Listing (/blog/category/{category})
     */
    public function category(string $category): View
    {
        $categories = BlogPost::categories();
        abort_unless(array_key_exists($category, $categories), 404);

        $categoryLabel = $categories[$category];

        $posts = BlogPost::published()
            ->where('category', $category)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        $categoryCounts = BlogPost::published()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->all();

        $seo = [
            'title' => $categoryLabel . ' Guides & Templates | Formnexa Blog',
            'description' => 'Read step-by-step tutorials, formats, and checklists on ' . strtolower($categoryLabel) . ' using Formnexa free PDF tools.',
            'canonical' => $posts->currentPage() > 1
                ? $posts->url($posts->currentPage())
                : route('blog.category', $category),
        ];

        return view('blog.index', [
            'posts' => $posts,
            'featuredPost' => null,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'activeCategory' => $category,
            'activeCategoryLabel' => $categoryLabel,
            'search' => '',
            'seo' => $seo,
        ]);
    }

    /**
     * Individual Blog Post Page (/blog/{slug})
     */
    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();

        if (!$post->is_published && (!Auth::check() || ($post->user_id !== null && $post->user_id !== Auth::id()))) {
            abort(404);
        }

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [$post->category])
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $seo = [
            'title' => $post->meta_title ?: ($post->title . ' | Formnexa'),
            'description' => $post->meta_description ?: Str::limit($post->excerpt, 155, ''),
            'canonical' => route('blog.show', $post->slug),
            'type' => 'article',
            'image' => $post->cover_image ?: config('seo.default_image', 'images/og/default.jpg'),
            'published_time' => ($post->published_at ?? $post->created_at)?->toIso8601String(),
            'modified_time' => ($post->updated_at ?? $post->published_at ?? $post->created_at)?->toIso8601String(),
        ];
        if (!$post->is_published) {
            $seo['robots'] = 'noindex,nofollow';
        }

        return view('blog.show', [
            'post' => $post,
            'toc' => $post->tableOfContents(),
            'renderedHtml' => $post->renderedContent(),
            'relatedPosts' => $relatedPosts,
            'seo' => $seo,
        ]);
    }

    /**
     * Authenticated Blog Studio Management List (/blog-studio)
     */
    public function manage(): View
    {
        $posts = BlogPost::orderByDesc('updated_at')->get();

        return view('blog.manage', [
            'posts' => $posts,
            'categories' => BlogPost::categories(),
            'seo' => ['robots' => 'noindex,nofollow'],
        ]);
    }

    /**
     * Authenticated Create Article Form (/blog-studio/create)
     */
    public function create(): View
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
            'seo' => ['robots' => 'noindex,nofollow', 'title' => 'New Blog Article | Formnexa'],
        ]);
    }

    /**
     * Store New Blog Article (/blog-studio)
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPayload($request);
        $data['user_id'] = Auth::id();

        $post = BlogPost::create($data);
        $this->refreshSitemapSafely();

        return redirect()
            ->route('blog.manage')
            ->with('success', 'Article "' . $post->title . '" saved and sitemap updated.');
    }

    /**
     * Edit Existing Article Form (/blog-studio/{post}/edit)
     */
    public function edit(BlogPost $post): View
    {
        return view('blog.form', [
            'post' => $post,
            'categories' => BlogPost::categories(),
            'toolOptions' => $this->toolOptions(),
            'isEdit' => true,
            'seo' => ['robots' => 'noindex,nofollow', 'title' => 'Edit Article | Formnexa'],
        ]);
    }

    /**
     * Update Existing Article (/blog-studio/{post})
     */
    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $this->validatedPayload($request, $post);
        $post->update($data);
        $this->refreshSitemapSafely();

        return redirect()
            ->route('blog.manage')
            ->with('success', 'Article "' . $post->title . '" updated and sitemap refreshed.');
    }

    /**
     * Delete Article (/blog-studio/{post})
     */
    public function destroy(BlogPost $post): RedirectResponse
    {
        $title = $post->title;
        $post->delete();
        $this->refreshSitemapSafely();

        return redirect()
            ->route('blog.manage')
            ->with('success', 'Article "' . $title . '" removed.');
    }

    /**
     * Validate create/update payload for SEO blog posts.
     */
    protected function validatedPayload(Request $request, ?BlogPost $existing = null): array
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
            'slug' => ['required', 'string', 'max:200', Rule::unique('blog_posts', 'slug')->ignore($existing?->id)],
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
            'published_at' => $isPublished ? ($existing?->published_at ?? now()) : $existing?->published_at,
        ];
    }

    /**
     * Tool dropdown options for linking articles cleanly back to revenue/utility tools.
     *
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
            // Non-blocking if filesystem is read-only
        }
    }
}
