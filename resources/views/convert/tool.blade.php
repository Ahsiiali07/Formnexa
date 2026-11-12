@extends('layouts.app')

@section('title', $meta['name'] . ' — Free Online Converter | Formnexa')
@section('meta_description', $meta['tagline'])

@section('content')
<div x-data="pdfConverterTool(@js($meta))" class="min-h-screen bg-[#F8FAFC] pb-16 sm:pb-20">

    {{-- Top Tool Switcher & Breadcrumb Strip --}}
    <section class="bg-white border-b border-border-subtle pt-6 sm:pt-7 pb-5 sm:pb-6 px-4 sm:px-6">
        <div class="max-w-[1280px] mx-auto space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2 text-xs text-[#565e74]">
                    <a href="{{ route('convert.hub') }}" class="font-bold text-[#0b1c30] hover:text-[#0066FF]">Convert PDF</a>
                    <span>/</span>
                    <span class="font-mono uppercase tracking-wider font-semibold">{{ $meta['group'] === 'to_pdf' ? 'Convert to PDF' : 'Convert from PDF' }}</span>
                    <span>/</span>
                    <span class="font-bold text-[#0b1c30]">{{ $meta['name'] }}</span>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F8FAFC] border border-border-subtle text-emerald-700 text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    100% Free &bull; No Sign-up Required
                </span>
            </div>

            <div class="flex items-start gap-3.5 sm:gap-4">
                <span class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl {{ $meta['color_bg'] }} {{ $meta['color_text'] }} border {{ $meta['color_border'] }} flex items-center justify-center font-mono font-bold text-sm sm:text-base shrink-0 shadow-sm">
                    {{ $meta['badge'] }}
                </span>
                <div class="min-w-0">
                    <h1 class="font-display text-2xl sm:text-4xl font-extrabold tracking-[-0.03em] text-[#0b1c30] leading-tight">{{ $meta['title'] }}</h1>
                    <p class="text-xs sm:text-sm text-[#565e74] mt-1 max-w-2xl">{{ $meta['tagline'] }}</p>
                </div>
            </div>

            {{-- Horizontal Quick Switcher Bar across all conversions --}}
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide pt-1 pb-0.5">
                @foreach(array_merge($groups['to_pdf'], $groups['from_pdf']) as $navTool)
                    <a href="{{ route('convert.tool', $navTool['slug']) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-bold whitespace-nowrap border transition {{ $navTool['slug'] === $meta['slug'] ? 'bg-[#0B0F19] text-white border-[#0B0F19] shadow-sm' : 'bg-[#F8FAFC] text-[#565e74] border-border-subtle hover:border-[#0066FF] hover:text-[#0b1c30]' }}">
                        {{ $navTool['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Main Two-Column Conversion Studio --}}
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 pt-6 sm:pt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-start">

            {{-- LEFT / MAIN WORKSPACE (8 cols) --}}
            <div class="lg:col-span-8 space-y-5">

                {{-- Error Banner --}}
                <div x-show="errorMsg" x-cloak
                     class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm font-semibold flex items-start justify-between gap-3">
                    <span x-text="errorMsg"></span>
                    <button type="button" @click="errorMsg = ''" class="text-rose-500 font-bold">&times;</button>
                </div>

                {{-- Success Download Card --}}
                <div x-show="downloadResult" x-cloak
                     class="p-6 rounded-2xl bg-emerald-50/90 border border-emerald-200 shadow-sm space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-black text-lg shrink-0">&#10003;</span>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Conversion Complete!</div>
                                <div class="text-xs text-slate-600 mt-0.5" x-text="(downloadResult?.filename || '') + ' (' + formatBytes(downloadResult?.size || 0) + ')'"></div>
                            </div>
                        </div>
                        <button type="button" @click="resetWorkspace()" class="text-xs font-bold text-slate-600 hover:text-slate-900 underline">Convert Another</button>
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        <button type="button" @click="triggerSavedDownload()"
                                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold shadow-sm transition">
                            Download File Again
                        </button>
                        <button type="button" @click="resetWorkspace()"
                                class="px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                            Convert Another File
                        </button>
                    </div>
                </div>

                {{-- SPECIALIZED WORKSPACE A: HTML TO PDF (File / Raw HTML / Public URL Tabs) --}}
                <template x-if="meta.slug === 'html-to-pdf'">
                    <div class="bg-white rounded-2xl border border-border-subtle shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-5 pt-4 pb-3 border-b border-border-subtle flex flex-wrap items-center justify-between gap-2 bg-[#F8FAFC]">
                            <div class="inline-flex flex-wrap p-1 bg-slate-200/70 rounded-xl gap-1 text-xs">
                                <button type="button" @click="htmlMode = 'code'"
                                        class="px-3 py-1.5 rounded-lg font-bold transition"
                                        :class="htmlMode === 'code' ? 'bg-white text-[#0b1c30] shadow-sm' : 'text-[#565e74]'">Paste HTML Code</button>
                                <button type="button" @click="htmlMode = 'file'"
                                        class="px-3 py-1.5 rounded-lg font-bold transition"
                                        :class="htmlMode === 'file' ? 'bg-white text-[#0b1c30] shadow-sm' : 'text-[#565e74]'">Upload .HTML File</button>
                                <button type="button" @click="htmlMode = 'url'"
                                        class="px-3 py-1.5 rounded-lg font-bold transition"
                                        :class="htmlMode === 'url' ? 'bg-white text-[#0b1c30] shadow-sm' : 'text-[#565e74]'">Webpage URL</button>
                            </div>
                            <button type="button" x-show="htmlMode === 'code'" @click="insertSampleHtml()"
                                    class="text-xs font-bold text-[#0066FF] hover:underline">
                                Load Starter Template
                            </button>
                        </div>

                        <div class="p-5 space-y-4">
                            <div x-show="htmlMode === 'code'" class="space-y-3">
                                <textarea x-model="htmlCode" rows="12"
                                          placeholder="Paste your clean <!DOCTYPE html> ... </html> or HTML snippet here..."
                                          class="w-full p-4 rounded-xl bg-slate-50 border border-slate-200 font-mono text-xs text-slate-800 focus:bg-white focus:border-[#0066FF] focus:outline-none"></textarea>
                                <div class="flex items-center justify-between text-[11px] text-slate-500">
                                    <span>Supports inline CSS, Flexbox, tables &amp; print stylesheets.</span>
                                    <button type="button" @click="showHtmlPreview = !showHtmlPreview"
                                            class="font-bold text-slate-800 hover:text-[#0066FF]"
                                            x-text="showHtmlPreview ? 'Hide Live Preview' : 'Show Live Preview'"></button>
                                </div>
                                <iframe x-show="showHtmlPreview" x-cloak :srcdoc="htmlCode"
                                        sandbox=""
                                        class="w-full h-72 rounded-xl border border-slate-200 bg-white"></iframe>
                            </div>

                            <div x-show="htmlMode === 'file'">
                                <label class="block p-10 rounded-2xl border-2 border-dashed transition text-center cursor-pointer"
                                       :class="dragging ? 'border-[#0066FF] bg-blue-50/50' : 'border-slate-300 bg-slate-50/60 hover:border-[#0066FF]'"
                                       @dragover.prevent="dragging = true"
                                       @dragleave.prevent="dragging = false"
                                       @drop.prevent="dragging = false; handleDrop($event)">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-black text-base mx-auto mb-3">&lt;/&gt;</div>
                                    <div class="text-sm font-extrabold text-slate-900" x-text="selectedFiles[0]?.name || 'Drop your .html / .htm file here or click to browse'"></div>
                                    <div class="text-xs text-slate-500 mt-1">Max file size 10 MB</div>
                                    <input type="file" accept=".html,.htm,.txt,text/html" @change="handleFileSelect($event)" class="hidden">
                                </label>
                            </div>

                            <div x-show="htmlMode === 'url'" class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Public Webpage URL</label>
                                <input type="url" x-model="htmlUrl" placeholder="https://example.com/article"
                                       class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-900 focus:bg-white focus:border-[#0066FF] focus:outline-none">
                                <p class="text-[11px] text-slate-500">Fetches public HTTPS pages safely and converts the document into printable PDF format.</p>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- WORKSPACE B: FILE DROPZONE & MULTI-IMAGE / PDF->JPG PREVIEWS --}}
                <template x-if="meta.slug !== 'html-to-pdf'">
                    <div class="bg-white rounded-2xl border border-border-subtle shadow-sm p-4 sm:p-6 space-y-5">

                        {{-- Dropzone --}}
                        <label class="block rounded-2xl border-2 border-dashed transition text-center cursor-pointer"
                               :class="[
                                   selectedFiles.length > 0 && meta.slug !== 'jpg-to-pdf' ? 'p-5' : 'p-7 sm:p-14',
                                   dragging ? 'border-[#0066FF] bg-[#EFF6FF]' : 'border-slate-300 bg-[#F8FAFC] hover:border-[#0066FF]'
                               ]"
                               @dragover.prevent="dragging = true"
                               @dragleave.prevent="dragging = false"
                               @drop.prevent="dragging = false; handleDrop($event)">
                            <span class="w-12 h-12 rounded-xl {{ $meta['color_bg'] }} {{ $meta['color_text'] }} border {{ $meta['color_border'] }} inline-flex items-center justify-center font-mono font-bold text-base mb-3 shadow-sm">
                                {{ $meta['badge'] }}
                            </span>
                            <div class="text-base font-extrabold text-slate-900">
                                <span x-show="selectedFiles.length === 0">Drop your file here or click to select</span>
                                <span x-show="selectedFiles.length > 0" x-text="meta.multiple ? 'Add more images (+' + selectedFiles.length + ' selected)' : 'Choose a different file'"></span>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">{{ $meta['accept_label'] }}</div>
                            <input type="file"
                                   :accept="meta.accept"
                                   :multiple="!!meta.multiple"
                                   @change="handleFileSelect($event)"
                                   class="hidden">
                        </label>

                        {{-- Multi-Image Reorder Grid for JPG -> PDF --}}
                        <div x-show="meta.slug === 'jpg-to-pdf' && selectedFiles.length > 0" x-cloak class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-700" x-text="selectedFiles.length + ' image(s) queued — reorder pages before merging into PDF'"></span>
                                <button type="button" @click="clearFiles()" class="text-rose-600 font-bold hover:underline">Clear All</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                <template x-for="(item, idx) in selectedFiles" :key="item.uid">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-2 flex flex-col justify-between gap-2">
                                        <div class="aspect-[3/4] rounded-lg overflow-hidden bg-white border border-slate-200/80 flex items-center justify-center relative">
                                            <img :src="item.previewUrl" alt="" class="w-full h-full object-contain">
                                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-slate-900/80 text-white text-[10px] font-bold" x-text="'#' + (idx + 1)"></span>
                                        </div>
                                        <div class="text-[10.5px] font-semibold text-slate-700 truncate" x-text="item.name"></div>
                                        <div class="flex items-center justify-between gap-1 text-[10px]">
                                            <div class="flex gap-1">
                                                <button type="button" @click="moveFile(idx, -1)" :disabled="idx === 0" class="px-1.5 py-1 rounded bg-white border border-slate-200 disabled:opacity-40">&larr;</button>
                                                <button type="button" @click="moveFile(idx, 1)" :disabled="idx === selectedFiles.length - 1" class="px-1.5 py-1 rounded bg-white border border-slate-200 disabled:opacity-40">&rarr;</button>
                                            </div>
                                            <button type="button" @click="removeFile(idx)" class="px-2 py-1 rounded bg-rose-50 text-rose-600 font-bold">&times;</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Single File Info Card (for Word / PPT / Excel / PDF tools) --}}
                        <div x-show="meta.slug !== 'jpg-to-pdf' && selectedFiles.length > 0" x-cloak
                             class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-10 h-10 rounded-xl {{ $meta['color_bg'] }} {{ $meta['color_text'] }} border {{ $meta['color_border'] }} flex items-center justify-center font-black text-xs shrink-0">
                                    {{ $meta['badge'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-slate-900 truncate" x-text="selectedFiles[0]?.name"></div>
                                    <div class="text-xs text-slate-500" x-text="formatBytes(selectedFiles[0]?.size || 0)"></div>
                                </div>
                            </div>
                            <button type="button" @click="clearFiles()" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:text-rose-600">Remove</button>
                        </div>

                        {{-- Rendered Page Preview Grid for PDF -> JPG --}}
                        <div x-show="meta.slug === 'pdf-to-jpg' && (renderingPdf || renderedJpgPages.length > 0)" x-cloak class="space-y-4 pt-2 border-t border-slate-100">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-extrabold text-slate-900" x-show="renderingPdf" x-text="statusText || 'Rendering PDF pages...'"></div>
                                    <div class="text-sm font-extrabold text-slate-900" x-show="!renderingPdf" x-text="'Rendered ' + renderedJpgPages.length + ' crisp JPG page(s)'"></div>
                                </div>
                                <button type="button" x-show="renderedJpgPages.length > 0 && !renderingPdf"
                                        @click="downloadAllJpgs()"
                                        class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-[#0066FF] text-white text-xs font-extrabold transition shadow-sm">
                                    Download All Pages (.zip)
                                </button>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <template x-for="pg in renderedJpgPages" :key="pg.pageNumber">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-2.5 space-y-2 flex flex-col justify-between">
                                        <div class="aspect-[1/1.41] rounded-lg overflow-hidden bg-white border border-slate-200 relative">
                                            <img :src="pg.dataUrl" alt="" class="w-full h-full object-contain">
                                            <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded bg-slate-900/80 text-white text-[10px] font-bold" x-text="'Page ' + pg.pageNumber"></span>
                                        </div>
                                        <div class="flex items-center justify-between gap-2 text-[10.5px]">
                                            <span class="text-slate-500 font-medium" x-text="pg.width + '×' + pg.height + 'px'"></span>
                                            <button type="button" @click="downloadSingleJpg(pg)"
                                                    class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-[#0066FF] text-slate-800 font-bold">
                                                Save JPG
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- RIGHT SETTINGS & CONVERT SIDEBAR (4 cols) --}}
            <aside class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-5 lg:sticky lg:top-24">
                <div>
                    <div class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Conversion Options</div>
                    <div class="text-base font-black text-slate-900 mt-0.5">{{ $meta['name'] }}</div>
                </div>

                {{-- Dynamic Options per Conversion Type --}}
                <div class="space-y-4 text-xs">
                    {{-- JPG -> PDF Options --}}
                    <template x-if="meta.slug === 'jpg-to-pdf'">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1.5">Page Size</label>
                                <select x-model="opts.page_size" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 font-semibold text-slate-800">
                                    <option value="a4">A4 (210 × 297 mm)</option>
                                    <option value="letter">US Letter (8.5 × 11 in)</option>
                                    <option value="fit">Fit to Exact Image Size</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 mb-1.5">Orientation</label>
                                <select x-model="opts.orientation" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 font-semibold text-slate-800">
                                    <option value="auto">Automatic (Match Images)</option>
                                    <option value="portrait">Portrait</option>
                                    <option value="landscape">Landscape</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 mb-1.5">Margins</label>
                                <div class="grid grid-cols-3 gap-1.5">
                                    <button type="button" @click="opts.margin = 'none'" class="py-2 rounded-lg border font-bold" :class="opts.margin === 'none' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">None</button>
                                    <button type="button" @click="opts.margin = 'small'" class="py-2 rounded-lg border font-bold" :class="opts.margin === 'small' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">Small</button>
                                    <button type="button" @click="opts.margin = 'normal'" class="py-2 rounded-lg border font-bold" :class="opts.margin === 'normal' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">Normal</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- WORD -> PDF / HTML -> PDF Options --}}
                    <template x-if="meta.slug === 'word-to-pdf' || meta.slug === 'html-to-pdf'">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1.5">Paper Format</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="opts.page_size = 'a4'" class="py-2 rounded-lg border font-bold" :class="opts.page_size === 'a4' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">A4</button>
                                    <button type="button" @click="opts.page_size = 'letter'" class="py-2 rounded-lg border font-bold" :class="opts.page_size === 'letter' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">US Letter</button>
                                </div>
                            </div>
                            <div x-show="meta.slug === 'html-to-pdf'">
                                <label class="block font-bold text-slate-600 mb-1.5">Orientation</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="opts.orientation = 'portrait'" class="py-2 rounded-lg border font-bold" :class="opts.orientation === 'portrait' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">Portrait</button>
                                    <button type="button" @click="opts.orientation = 'landscape'" class="py-2 rounded-lg border font-bold" :class="opts.orientation === 'landscape' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">Landscape</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- EXCEL -> PDF Options --}}
                    <template x-if="meta.slug === 'excel-to-pdf'">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1.5">Orientation</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="opts.orientation = 'landscape'" class="py-2 rounded-lg border font-bold" :class="opts.orientation === 'landscape' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">Landscape</button>
                                    <button type="button" @click="opts.orientation = 'portrait'" class="py-2 rounded-lg border font-bold" :class="opts.orientation === 'portrait' ? 'border-[#0066FF] bg-blue-50 text-[#0066FF]' : 'border-slate-200 text-slate-600'">Portrait</button>
                                </div>
                            </div>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                                <input type="checkbox" x-model="opts.gridlines" class="rounded border-slate-300 text-[#0066FF]">
                                <span class="font-bold text-slate-700">Show cell gridlines in PDF</span>
                            </label>
                        </div>
                    </template>

                    {{-- PDF -> JPG Options --}}
                    <template x-if="meta.slug === 'pdf-to-jpg'">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-bold text-slate-600 mb-1.5">Output Resolution (DPI)</label>
                                <select x-model="opts.scale" @change="if(selectedFiles.length) renderPdfPreview()" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 font-semibold text-slate-800">
                                    <option value="1.5">Standard Quality (150 DPI)</option>
                                    <option value="2.0">High Quality (200 DPI)</option>
                                    <option value="2.75">Ultra Crisp (300 DPI)</option>
                                </select>
                            </div>
                        </div>
                    </template>

                    {{-- PDF -> WORD Options --}}
                    <template x-if="meta.slug === 'pdf-to-word'">
                        <div class="space-y-2">
                            <label class="block font-bold text-slate-600 mb-1">Extraction Mode</label>
                            <button type="button" @click="opts.mode = 'editable'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.mode === 'editable' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">Editable Text &amp; Structure</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Extracts selectable headings, bullets &amp; paragraphs into native Word text.</div>
                            </button>
                            <button type="button" @click="opts.mode = 'visual'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.mode === 'visual' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">Exact Visual Layout (Scanned PDFs)</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Preserves complex graphics &amp; scanned pages frame-by-frame inside DOCX.</div>
                            </button>
                        </div>
                    </template>

                    {{-- PDF -> POWERPOINT Options --}}
                    <template x-if="meta.slug === 'pdf-to-powerpoint'">
                        <div class="space-y-2">
                            <label class="block font-bold text-slate-600 mb-1">Slide Conversion Mode</label>
                            <button type="button" @click="opts.mode = 'visual'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.mode === 'visual' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">Exact Visual Slides (Recommended)</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Each PDF page becomes a high-res 16:9 widescreen slide preserving design.</div>
                            </button>
                            <button type="button" @click="opts.mode = 'editable'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.mode === 'editable' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">Editable Text &amp; Bullets</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Reconstructs editable text titles and bullet points on slides.</div>
                            </button>
                        </div>
                    </template>

                    {{-- PDF -> EXCEL Options --}}
                    <template x-if="meta.slug === 'pdf-to-excel'">
                        <div class="space-y-2">
                            <label class="block font-bold text-slate-600 mb-1">Worksheet Layout</label>
                            <button type="button" @click="opts.merge_sheets = 'per_page'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.merge_sheets === 'per_page' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">One Sheet per PDF Page</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Best for multi-page financial statements or invoices.</div>
                            </button>
                            <button type="button" @click="opts.merge_sheets = 'single_sheet'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.merge_sheets === 'single_sheet' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">Combine All Tables on One Sheet</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Stacks extracted rows vertically into one continuous sheet.</div>
                            </button>
                        </div>
                    </template>

                    {{-- PDF -> PDF/A Options --}}
                    <template x-if="meta.slug === 'pdf-to-pdfa'">
                        <div class="space-y-2">
                            <label class="block font-bold text-slate-600 mb-1">ISO Archival Conformance</label>
                            <button type="button" @click="opts.conformance = '1b'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.conformance === '1b' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">PDF/A-1b (ISO 19005-1)</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Strict long-term archiving standard with sRGB ICC profile &amp; XMP metadata.</div>
                            </button>
                            <button type="button" @click="opts.conformance = '3b'"
                                    class="w-full text-left p-3 rounded-xl border transition"
                                    :class="opts.conformance === '3b' ? 'border-[#0066FF] bg-blue-50/70' : 'border-slate-200'">
                                <div class="font-bold text-slate-900">PDF/A-3b (ISO 19005-3)</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Modern archival standard supporting richer document compliance metadata.</div>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Convert CTA --}}
                <div class="pt-2 space-y-2.5">
                    <button type="button"
                            @click="runConversion()"
                            :disabled="converting || renderingPdf"
                            class="w-full py-3.5 px-5 rounded-xl bg-slate-900 hover:bg-[#0066FF] disabled:opacity-50 text-white text-sm font-extrabold shadow-md transition flex items-center justify-center gap-2">
                        <svg x-show="converting || renderingPdf" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span x-text="converting ? (statusText || 'Converting...') : (meta.slug === 'pdf-to-jpg' ? 'Download JPG Pages' : meta.button_label)"></span>
                    </button>

                    <div class="text-[11px] text-slate-400 text-center leading-snug">
                        Fast, private processing with no account or email required.
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function pdfConverterTool(meta) {
    return {
        meta,
        dragging: false,
        selectedFiles: [],
        converting: false,
        renderingPdf: false,
        statusText: '',
        errorMsg: '',
        downloadResult: null,
        renderedJpgPages: [],

        htmlMode: 'code',
        htmlCode: `<!DOCTYPE html>\n<html>\n<head>\n<style>\n  body { font-family: Arial, sans-serif; color: #0f172a; padding: 32px; }\n  .card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; }\n  h1 { color: #0066ff; margin-top: 0; }\n</style>\n</head>\n<body>\n  <div class="card">\n    <h1>Project Status Summary</h1>\n    <p>Converted cleanly to crisp vector PDF using Formnexa HTML to PDF.</p>\n  </div>\n</body>\n</html>`,
        htmlUrl: '',
        showHtmlPreview: false,

        opts: {
            page_size: 'a4',
            orientation: meta.slug === 'excel-to-pdf' ? 'landscape' : (meta.slug === 'html-to-pdf' ? 'portrait' : 'auto'),
            margin: 'small',
            gridlines: true,
            scale: '2.0',
            mode: meta.slug === 'pdf-to-powerpoint' ? 'visual' : 'editable',
            merge_sheets: 'per_page',
            conformance: '1b',
        },

        handleDrop(e) {
            const list = Array.from(e.dataTransfer?.files || []);
            this.addFiles(list);
        },

        handleFileSelect(e) {
            const list = Array.from(e.target?.files || []);
            this.addFiles(list);
            e.target.value = '';
        },

        addFiles(list) {
            if (!list.length) return;
            this.errorMsg = '';
            this.downloadResult = null;

            if (this.meta.multiple) {
                for (const f of list) {
                    this.selectedFiles.push({
                        uid: Math.random().toString(36).slice(2),
                        file: f,
                        name: f.name,
                        size: f.size,
                        previewUrl: f.type.startsWith('image/') ? URL.createObjectURL(f) : '',
                    });
                }
            } else {
                const f = list[0];
                this.selectedFiles = [{
                    uid: Math.random().toString(36).slice(2),
                    file: f,
                    name: f.name,
                    size: f.size,
                    previewUrl: '',
                }];
                if (this.meta.slug === 'pdf-to-jpg') {
                    this.renderPdfPreview();
                }
            }
        },

        moveFile(idx, dir) {
            const target = idx + dir;
            if (target < 0 || target >= this.selectedFiles.length) return;
            const copy = [...this.selectedFiles];
            [copy[idx], copy[target]] = [copy[target], copy[idx]];
            this.selectedFiles = copy;
        },

        removeFile(idx) {
            const item = this.selectedFiles[idx];
            if (item?.previewUrl) URL.revokeObjectURL(item.previewUrl);
            this.selectedFiles.splice(idx, 1);
        },

        clearFiles() {
            for (const item of this.selectedFiles) {
                if (item.previewUrl) URL.revokeObjectURL(item.previewUrl);
            }
            this.selectedFiles = [];
            this.renderedJpgPages = [];
            this.downloadResult = null;
            this.errorMsg = '';
        },

        resetWorkspace() {
            this.clearFiles();
            this.statusText = '';
        },

        insertSampleHtml() {
            this.htmlCode = `<!DOCTYPE html>
<html>
<head>
<style>
  body { font-family: Arial, sans-serif; color: #1e293b; padding: 32px; }
  .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0066ff; padding-bottom: 12px; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; margin-top: 16px; }
  th { background: #0f172a; color: #fff; text-align: left; padding: 10px; }
  td { border-bottom: 1px solid #e2e8f0; padding: 10px; }
</style>
</head>
<body>
  <div class="header">
    <div><strong>Formnexa Consulting</strong><br><small>Quarterly Statement</small></div>
    <div><strong>Date:</strong> ${new Date().toISOString().slice(0, 10)}</div>
  </div>
  <table>
    <thead><tr><th>Deliverable</th><th>Hours</th><th>Total</th></tr></thead>
    <tbody>
      <tr><td>Architecture &amp; UI Redesign</td><td>24</td><td>$3,600.00</td></tr>
      <tr><td>PDF Automation Pipeline</td><td>16</td><td>$2,400.00</td></tr>
    </tbody>
  </table>
</body>
</html>`;
        },

        async renderPdfPreview() {
            const f = this.selectedFiles[0]?.file;
            if (!f || !window.FormnexaPdf) return;
            this.renderingPdf = true;
            this.errorMsg = '';
            this.renderedJpgPages = [];
            try {
                const res = await window.FormnexaPdf.renderPdfPagesToJpgs(f, {
                    scale: Number(this.opts.scale || 2.0),
                    quality: 0.90,
                    maxPages: 40,
                    onProgress: (p, tot) => { this.statusText = `Rendering page ${p} of ${tot}...`; },
                });
                this.renderedJpgPages = res.pages;
                this.statusText = '';
            } catch (err) {
                console.error(err);
                this.errorMsg = 'Could not render this PDF in the browser: ' + (err?.message || 'Invalid PDF file.');
            } finally {
                this.renderingPdf = false;
            }
        },

        downloadSingleJpg(pg) {
            const base = (this.selectedFiles[0]?.name || 'document').replace(/\.pdf$/i, '');
            const padded = String(pg.pageNumber).padStart(2, '0');
            window.FormnexaPdf.downloadBlob(pg.blob, `${base}-page-${padded}.jpg`);
        },

        async downloadAllJpgs() {
            if (!this.renderedJpgPages.length) return;
            const base = this.selectedFiles[0]?.name || 'pdf-pages';
            if (this.renderedJpgPages.length === 1) {
                this.downloadSingleJpg(this.renderedJpgPages[0]);
                return;
            }
            await window.FormnexaPdf.downloadJpgsZip(this.renderedJpgPages, base);
        },

        async runConversion() {
            this.errorMsg = '';

            if (this.meta.slug === 'pdf-to-jpg') {
                if (!this.selectedFiles.length) {
                    this.errorMsg = 'Please select a PDF file first.';
                    return;
                }
                if (window.FormnexaPdf) {
                    if (!this.renderedJpgPages.length) {
                        await this.renderPdfPreview();
                    }
                    await this.downloadAllJpgs();
                    return;
                }
            }

            if (this.meta.slug === 'html-to-pdf') {
                if (this.htmlMode === 'file' && !this.selectedFiles.length) {
                    this.errorMsg = 'Please upload an .html file.';
                    return;
                }
                if (this.htmlMode === 'code' && !this.htmlCode.trim()) {
                    this.errorMsg = 'Please paste HTML markup first.';
                    return;
                }
                if (this.htmlMode === 'url' && !this.htmlUrl.trim()) {
                    this.errorMsg = 'Please enter a valid public URL.';
                    return;
                }
            } else if (!this.selectedFiles.length) {
                this.errorMsg = 'Please select a file to convert first.';
                return;
            }

            this.converting = true;
            this.downloadResult = null;
            this.statusText = 'Preparing conversion...';

            try {
                const fd = new FormData();
                if (this.meta.multiple) {
                    this.selectedFiles.forEach((item) => fd.append('files[]', item.file));
                } else if (this.selectedFiles[0]?.file) {
                    fd.append('file', this.selectedFiles[0].file);
                }

                if (this.meta.slug === 'html-to-pdf') {
                    if (this.htmlMode === 'code') fd.append('html_code', this.htmlCode);
                    if (this.htmlMode === 'url') fd.append('url', this.htmlUrl);
                }

                fd.append('page_size', this.opts.page_size);
                fd.append('orientation', this.opts.orientation);
                fd.append('margin', this.opts.margin);
                fd.append('gridlines', this.opts.gridlines ? '1' : '0');
                fd.append('mode', this.opts.mode);
                fd.append('merge_sheets', this.opts.merge_sheets);
                fd.append('conformance', this.opts.conformance);
                if (this.meta.slug === 'pdf-to-jpg') {
                    fd.append('scale', this.opts.scale || '2.0');
                    fd.append('output', 'auto');
                }

                // For PDF/A & Visual PowerPoint/Word modes, render crisp page frames in browser via PDF.js
                const needsFrames = (
                    this.meta.slug === 'pdf-to-pdfa'
                    || (this.meta.slug === 'pdf-to-powerpoint' && this.opts.mode === 'visual')
                    || (this.meta.slug === 'pdf-to-word' && this.opts.mode === 'visual')
                );

                if (needsFrames && this.selectedFiles[0]?.file && window.FormnexaPdf) {
                    try {
                        const rendered = await window.FormnexaPdf.renderPdfPagesToJpgs(this.selectedFiles[0].file, {
                            scale: 1.75,
                            quality: 0.88,
                            maxPages: 25,
                            onProgress: (p, tot) => { this.statusText = `Preparing page ${p} of ${tot}...`; },
                        });
                        rendered.pages.forEach((pg, idx) => {
                            if (pg.blob) fd.append('page_frames[]', pg.blob, `page-${idx + 1}.jpg`);
                        });
                    } catch (renderErr) {
                        console.warn('Client PDF rasterizer skipped, using server parser fallback:', renderErr);
                    }
                }

                this.statusText = 'Converting document...';
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const endpoint = @js(url('/api/convert')) + '/' + this.meta.slug;

                const resp = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: fd,
                });

                const contentType = resp.headers.get('content-type') || '';
                if (!resp.ok || contentType.includes('application/json')) {
                    const errJson = await resp.json().catch(() => ({}));
                    this.errorMsg = errJson.message || 'Conversion failed. Please verify the file and try again.';
                    return;
                }

                const blob = await resp.blob();
                const headerName = resp.headers.get('X-Converted-Filename');
                const fallbackBase = (this.selectedFiles[0]?.name || 'converted').replace(/\.[^.]+$/, '');
                const filename = headerName || `${fallbackBase}.${this.meta.output_ext}`;

                this.downloadResult = { blob, filename, size: blob.size };
                this.triggerSavedDownload();
            } catch (e) {
                console.error(e);
                this.errorMsg = 'Network error while converting file.';
            } finally {
                this.converting = false;
                this.statusText = '';
            }
        },

        triggerSavedDownload() {
            if (!this.downloadResult?.blob) return;
            if (window.FormnexaPdf?.downloadBlob) {
                window.FormnexaPdf.downloadBlob(this.downloadResult.blob, this.downloadResult.filename);
                return;
            }
            const url = URL.createObjectURL(this.downloadResult.blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = this.downloadResult.filename || 'converted-file';
            document.body.appendChild(link);
            link.click();
            link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 2500);
        },

        formatBytes(bytes) {
            if (!bytes) return '0 B';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(2) + ' MB';
        },
    };
}
</script>
@endpush
