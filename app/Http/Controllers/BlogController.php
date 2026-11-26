<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\IndexNowService;
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
    public function index(Request $request): View|RedirectResponse
    {
        // 301 redirect /blog?page=1 to /blog to prevent duplicate content & meta description issues
        if ($request->query('page') === '1') {
            $params = $request->query();
            unset($params['page']);
            return redirect()->route('blog.index', $params, 301);
        }

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

        $topicGroups = [];
        if ($search === '' && $posts->currentPage() === 1) {
            $topicGroups = [
                'Resume and ATS' => BlogPost::published()->where('category', 'resumes')->orderByDesc('published_at')->take(4)->get(),
                'Careers' => BlogPost::published()->where('category', 'business-docs')->where('slug', 'like', '%resign%')->orderByDesc('published_at')->take(4)->get(),
                'Invoicing and Payroll' => BlogPost::published()->whereIn('category', ['invoicing', 'global-billing'])->orderByDesc('published_at')->take(4)->get(),
            ];
        }

        $seo = [
            'canonical' => route('blog.index'),
        ];

        if ($search !== '') {
            $seo['robots'] = 'noindex,follow';
            $seo['title'] = 'Search Guides: ' . Str::limit($search, 30) . ' | Formnexa';
            $seo['description'] = 'Search results for ' . Str::limit($search, 40) . ' across Formnexa guides, templates, and PDF tools.';
        } elseif ($posts->currentPage() > 1) {
            $seo['canonical'] = $posts->url($posts->currentPage());
            $seo['title'] = 'Blog Guides – Page ' . $posts->currentPage() . ' | Formnexa';
            $seo['description'] = 'Explore page ' . $posts->currentPage() . ' of Formnexa guides on invoice formats, ATS resumes, PDF conversion workflows, and billing templates.';
        }

        return view('blog.index', [
            'posts' => $posts,
            'featuredPost' => $featuredPost,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'topicGroups' => $topicGroups,
            'activeCategory' => null,
            'search' => $search,
            'seo' => $seo,
        ]);
    }

    /**
     * Public Blog Category Listing (/blog/category/{category})
     */
    public function category(string $category): View|RedirectResponse
    {
        // 301 redirect /blog/category/name?page=1 to /blog/category/name
        if (request()->query('page') === '1') {
            return redirect()->route('blog.category', ['category' => $category], 301);
        }

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

        $categoryDetails = [
            'invoicing' => [
                'title' => 'Invoicing & Billing Guides – Formats, Payment Terms & Tips | Formnexa Blog',
                'description' => 'Master client billing with step-by-step invoice guides, payment term strategies, itemized tax breakdowns, and automated invoice templates for freelancers and agencies.',
                'intro' => 'Clear, professional invoicing is the foundation of dependable cash flow. Whether you are freelancing, running a digital agency, or managing a trade business, our invoicing guides cover essential billing practices—from structuring standard itemized invoices and setting fair net-30 payment terms to handling overdue balances with polite reminder schedules. Learn how to calculate local sales taxes, add direct payment links, and choose the right billing frequency for your projects. Pair each walkthrough with Formnexa\'s free invoice generator to produce polished, watermark-free PDF bills that help you get paid faster.',
            ],
            'resumes' => [
                'title' => 'ATS Resume & CV Guides – Formatting Rules & Layouts | Formnexa Blog',
                'description' => 'Learn how to write an ATS-friendly resume that passes automated recruiter screening. Practical formatting checklists, keyword optimization tips, and free templates.',
                'intro' => 'Modern hiring relies heavily on Applicant Tracking Systems (ATS) like Workday, Greenhouse, and Taleo to screen candidates before human eyes ever see an application. Our resume guides provide straightforward, evidence-based advice on passing automated parsers without sacrificing visual elegance. Learn how to structure single-column layouts, organize standard sections (Experience, Education, and Skills), write outcome-driven bullet points with measurable metrics, and tailor keywords to specific job postings. Use these guides alongside Formnexa\'s ATS CV Converter and live resume builder to create clean, recruiter-ready resumes in PDF and DOCX.',
            ],
            'pdf-tools' => [
                'title' => 'PDF & Document Conversion Guides – PDF to Word, JPG & Archival | Formnexa Blog',
                'description' => 'Step-by-step guides for converting PDF to editable Word, JPG to PDF, and PDF/A archival standards without losing typography or layout integrity.',
                'intro' => 'Working with digital documents shouldn\'t mean wrestling with broken layouts, lost fonts, or scrambled tables. Our PDF and file conversion guides explain the mechanics of document transformation—how vector text differs from scanned imagery, how to convert PDFs into editable Microsoft Word (.docx) files while preserving column structure, and when to use ISO-standardized PDF/A formats for long-term legal archival. Whether you need to turn smartphone photos into crisp multi-page PDFs or export lightweight files for email attachments, explore our tutorials and companion converter tools built for speed and security.',
            ],
            'business-docs' => [
                'title' => 'Business Operations & Commercial Document Guides | Formnexa Blog',
                'description' => 'Understand the difference between quotes, purchase orders, work orders, receipts, and packing slips with clear workflows and free document generators.',
                'intro' => 'Every commercial transaction relies on a chain of operational documents—from initial quotations and formal estimates to purchase orders, work orders, packing slips, and final payment receipts. Our business operations guides clarify the legal and administrative roles of each document in commerce, helping you avoid costly miscommunications with vendors and clients. Discover when to issue an estimate versus a binding quote, how to verify goods received against purchase orders, and how to maintain audit-ready digital paperwork using Formnexa\'s free commercial document generators.',
            ],
            'global-billing' => [
                'title' => 'International Billing Guides – Multi-Currency, Cross-Border & Tax | Formnexa Blog',
                'description' => 'Practical guides for invoicing international clients across the US, Europe, Asia, and the Middle East. Multi-currency conversions, VAT/GST rules, and SWIFT/IBAN setups.',
                'intro' => 'Billing international clients requires navigating diverse currency conventions, foreign exchange margins, cross-border payment rails, and distinct regional tax obligations. Our global billing guides offer practical roadmaps for remote professionals, exporters, and international contractors working across North America, Europe, Asia, and the Middle East. Learn how to specify correct SWIFT/BIC codes and IBAN details, account for reverse-charge VAT or GST rules, lock in agreed exchange rates, and select lower-cost payment gateways. Streamline your global invoicing with Formnexa\'s multi-currency PDF billing tools.',
            ],
        ];

        $detail = $categoryDetails[$category] ?? null;
        $categoryIntro = $detail['intro'] ?? null;

        $catTitle = $detail['title'] ?? ($categoryLabel . ' Guides & Templates | Formnexa Blog');
        $catDesc = $detail['description'] ?? ('Read step-by-step tutorials, formats, and checklists on ' . strtolower($categoryLabel) . ' using Formnexa free PDF tools.');

        if ($posts->currentPage() > 1) {
            $catTitle = $categoryLabel . ' Guides – Page ' . $posts->currentPage() . ' | Formnexa Blog';
            $catDesc = 'Explore page ' . $posts->currentPage() . ' of ' . strtolower($categoryLabel) . ' guides, formats, and checklists on Formnexa.';
        }

        $seo = [
            'title' => $catTitle,
            'description' => $catDesc,
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
            'categoryIntro' => $categoryIntro,
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

        $resumeTemplates = [];
        if ($post->category === 'resumes') {
            $all = \App\Support\ResumeCatalog::templates();
            $curatedSlugs = ['harvard-wallstreet', 'zurich-swiss', 'linear-founding-engineer', 'atlantic-flow'];
            $resumeTemplates = array_values(array_filter($all, fn ($t) => in_array($t['slug'], $curatedSlugs, true)));
            if (empty($resumeTemplates)) {
                $resumeTemplates = array_slice($all, 0, 4);
            }
        }

        return view('blog.show', [
            'post' => $post,
            'toc' => $post->tableOfContents(),
            'renderedHtml' => $post->renderedContent(),
            'relatedPosts' => $relatedPosts,
            'resumeTemplates' => $resumeTemplates,
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
        $this->pingIndexNowIfPublished($post);

        return redirect(route('blog.manage', [], false))
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
        $this->pingIndexNowIfPublished($post);

        return redirect(route('blog.manage', [], false))
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

        return redirect(route('blog.manage', [], false))
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
            Artisan::call('seo:sitemap', ['--url' => 'https://formnexa.dcpakpattan.gov.pk']);
        } catch (\Throwable) {
            // Non-blocking if filesystem is read-only
        }
    }

    protected function pingIndexNowIfPublished(BlogPost $post): void
    {
        if (!$post->is_published) {
            return;
        }

        $domain = 'https://formnexa.dcpakpattan.gov.pk';
        app(IndexNowService::class)->autoPingUrls([
            $domain . '/blog/' . ltrim($post->slug, '/'),
            $domain . '/blog',
            $domain . '/',
        ], $domain);
    }
}
