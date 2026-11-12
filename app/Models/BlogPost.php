<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'category_label',
        'excerpt',
        'content',
        'cover_image',
        'featured_tool_route',
        'featured_tool_label',
        'meta_title',
        'meta_description',
        'focus_keyword',
        'reading_time_minutes',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'reading_time_minutes' => 'integer',
        ];
    }

    public static function categories(): array
    {
        return [
            'invoicing' => 'Invoicing & Billing',
            'resumes' => 'Resumes & ATS',
            'pdf-tools' => 'PDF & File Conversion',
            'business-docs' => 'Business Operations',
            'global-billing' => 'Asia, USA & Europe Billing',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveToolUrl(): ?string
    {
        if (!$this->featured_tool_route) {
            return null;
        }

        if (str_starts_with($this->featured_tool_route, '/')) {
            return url($this->featured_tool_route);
        }

        if (str_contains($this->featured_tool_route, ':')) {
            [$routeName, $param] = explode(':', $this->featured_tool_route, 2);
            return route($routeName, $param);
        }

        return route($this->featured_tool_route);
    }

    /**
     * Extract H2 headings for sticky Table of Contents navigation.
     *
     * @return array<int, array{id: string, text: string}>
     */
    public function tableOfContents(): array
    {
        $items = [];
        $lines = preg_split('/\r?\n/', (string) $this->content) ?: [];

        foreach ($lines as $line) {
            if (preg_match('/^##\s+(.+)$/', trim($line), $m)) {
                $heading = trim($m[1]);
                $items[] = [
                    'id' => Str::slug($heading),
                    'text' => $heading,
                ];
            }
        }

        return $items;
    }

    /**
     * Convert structured Markdown content into clean, XSS-safe, FormNexa-styled HTML.
     */
    public function renderedContent(): string
    {
        $lines = preg_split('/\r?\n/', (string) $this->content) ?: [];
        $html = '';
        $inUl = false;
        $inOl = false;
        $inTable = false;
        $tableHeaderDone = false;

        $closeLists = static function () use (&$html, &$inUl, &$inOl, &$inTable, &$tableHeaderDone): void {
            if ($inUl) {
                $html .= "</ul>\n";
                $inUl = false;
            }
            if ($inOl) {
                $html .= "</ol>\n";
                $inOl = false;
            }
            if ($inTable) {
                $html .= "</tbody></table></div>\n";
                $inTable = false;
                $tableHeaderDone = false;
            }
        };

        $inline = static function (string $text): string {
            $escaped = e($text);
            // Inline code
            $escaped = preg_replace('/`([^`]+)`/', '<code class="px-1.5 py-0.5 rounded bg-[#f8f8f3] border border-[#dfe4df] font-mono text-[13px] text-[#184f3a]">$1</code>', $escaped) ?? $escaped;
            // Bold
            $escaped = preg_replace('/\*\*([^*]+)\*\*/', '<strong class="font-bold text-[#17211b]">$1</strong>', $escaped) ?? $escaped;
            // Internal / external Markdown links [text](url)
            $escaped = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', static function (array $m): string {
                $label = $m[1];
                $rawUrl = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
                $safeUrl = str_starts_with($rawUrl, '/') || str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')
                    ? e($rawUrl)
                    : '#';
                $target = str_starts_with($rawUrl, 'http') ? ' target="_blank" rel="noopener noreferrer"' : '';
                return '<a href="' . $safeUrl . '"' . $target . ' class="text-[#184f3a] font-semibold underline decoration-[#c8f3dc] decoration-2 underline-offset-4 hover:decoration-[#184f3a] transition">' . $label . '</a>';
            }, $escaped) ?? $escaped;

            return $escaped;
        };

        foreach ($lines as $rawLine) {
            $line = trim($rawLine);

            if ($line === '') {
                $closeLists();
                continue;
            }

            // Markdown table rows | A | B |
            if (str_starts_with($line, '|') && str_ends_with($line, '|')) {
                if ($inUl) {
                    $html .= "</ul>\n";
                    $inUl = false;
                }
                if ($inOl) {
                    $html .= "</ol>\n";
                    $inOl = false;
                }

                $cells = array_map('trim', explode('|', trim($line, '|')));
                // Separator row |---|---|
                $isSeparator = collect($cells)->every(fn ($c) => preg_match('/^:?-{2,}:?$/', $c) === 1);
                if ($isSeparator) {
                    continue;
                }

                if (!$inTable) {
                    $inTable = true;
                    $tableHeaderDone = false;
                    $html .= '<div class="my-6 overflow-x-auto rounded-2xl border border-[#dfe4df] bg-white"><table class="w-full text-left border-collapse text-sm">';
                }

                if (!$tableHeaderDone) {
                    $html .= '<thead class="bg-[#f8f8f3] border-b border-[#dfe4df]"><tr>';
                    foreach ($cells as $cell) {
                        $html .= '<th class="px-4 py-3 font-bold text-[#17211b] whitespace-nowrap">' . $inline($cell) . '</th>';
                    }
                    $html .= '</tr></thead><tbody class="divide-y divide-[#edf1ed]">';
                    $tableHeaderDone = true;
                } else {
                    $html .= '<tr class="hover:bg-[#fbfbf7]">';
                    foreach ($cells as $cell) {
                        $html .= '<td class="px-4 py-3 text-[#46534b]">' . $inline($cell) . '</td>';
                    }
                    $html .= '</tr>';
                }
                continue;
            }

            if (preg_match('/^##\s+(.+)$/', $line, $m)) {
                $closeLists();
                $id = Str::slug(trim($m[1]));
                $html .= '<h2 id="' . e($id) . '" class="scroll-mt-24 font-display text-2xl sm:text-[26px] font-extrabold text-[#17211b] tracking-tight mt-10 mb-4">' . $inline(trim($m[1])) . "</h2>\n";
                continue;
            }

            if (preg_match('/^###\s+(.+)$/', $line, $m)) {
                $closeLists();
                $html .= '<h3 class="font-display text-lg sm:text-xl font-bold text-[#17211b] tracking-tight mt-7 mb-2.5">' . $inline(trim($m[1])) . "</h3>\n";
                continue;
            }

            if (str_starts_with($line, '> ')) {
                $closeLists();
                $quote = trim(substr($line, 2));
                $html .= '<blockquote class="my-6 pl-5 pr-4 py-3.5 rounded-r-2xl border-l-4 border-[#184f3a] bg-[#e8f7ed]/55 text-[#17211b] text-sm sm:text-[15px] leading-relaxed">' . $inline($quote) . "</blockquote>\n";
                continue;
            }

            if (preg_match('/^[-*]\s+(.+)$/', $line, $m)) {
                if ($inOl) {
                    $html .= "</ol>\n";
                    $inOl = false;
                }
                if ($inTable) {
                    $html .= "</tbody></table></div>\n";
                    $inTable = false;
                }
                if (!$inUl) {
                    $html .= '<ul class="my-4 space-y-2.5 pl-5 list-disc marker:text-[#184f3a] text-[#46534b] leading-relaxed">' . "\n";
                    $inUl = true;
                }
                $html .= '<li>' . $inline($m[1]) . "</li>\n";
                continue;
            }

            if (preg_match('/^\d+\.\s+(.+)$/', $line, $m)) {
                if ($inUl) {
                    $html .= "</ul>\n";
                    $inUl = false;
                }
                if ($inTable) {
                    $html .= "</tbody></table></div>\n";
                    $inTable = false;
                }
                if (!$inOl) {
                    $html .= '<ol class="my-4 space-y-2.5 pl-5 list-decimal marker:font-bold marker:text-[#184f3a] text-[#46534b] leading-relaxed">' . "\n";
                    $inOl = true;
                }
                $html .= '<li>' . $inline($m[1]) . "</li>\n";
                continue;
            }

            $closeLists();
            $html .= '<p class="my-4 text-[15px] sm:text-base text-[#3f4a43] leading-[1.78]">' . $inline($line) . "</p>\n";
        }

        $closeLists();

        return $html;
    }
}
