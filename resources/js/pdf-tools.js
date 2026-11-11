import * as pdfjsLib from 'pdfjs-dist';
import JSZip from 'jszip';

function ensurePdfWorker() {
    const meta = document.querySelector('meta[name="pdf-worker-url"]');
    if (meta && meta.content) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = meta.content;
    }
}

/**
 * Render all (or capped maxPages) pages of a PDF File into crisp JPEG canvases + Blobs + DataURLs.
 */
export async function renderPdfPagesToJpgs(file, options = {}) {
    ensurePdfWorker();

    const scale = Number(options.scale || 2.0);
    const quality = Number(options.quality || 0.90);
    const maxPages = Number(options.maxPages || 40);
    const onProgress = typeof options.onProgress === 'function' ? options.onProgress : null;

    const arrayBuffer = await file.arrayBuffer();
    const loadingTask = pdfjsLib.getDocument({ data: new Uint8Array(arrayBuffer) });
    const pdfDoc = await loadingTask.promise;

    const totalPages = Math.min(pdfDoc.numPages, maxPages);
    const pages = [];

    for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
        if (onProgress) onProgress(pageNum, totalPages);

        const page = await pdfDoc.getPage(pageNum);
        const viewport = page.getViewport({ scale });

        const canvas = document.createElement('canvas');
        canvas.width = Math.max(1, Math.floor(viewport.width));
        canvas.height = Math.max(1, Math.floor(viewport.height));

        const ctx = canvas.getContext('2d', { alpha: false });
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        await page.render({ canvasContext: ctx, viewport }).promise;

        const dataUrl = canvas.toDataURL('image/jpeg', quality);
        const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', quality));

        pages.push({
            pageNumber: pageNum,
            totalInDoc: pdfDoc.numPages,
            width: canvas.width,
            height: canvas.height,
            dataUrl,
            blob,
        });

        canvas.width = 0;
        canvas.height = 0;
    }

    return {
        numPages: pdfDoc.numPages,
        renderedCount: pages.length,
        pages,
    };
}

export function downloadBlob(blob, filename) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    setTimeout(() => URL.revokeObjectURL(url), 2500);
}

export async function downloadJpgsZip(pages, baseName = 'pdf-pages') {
    const zip = new JSZip();
    const cleanBase = baseName.replace(/\.pdf$/i, '').replace(/[^A-Za-z0-9\-_]+/g, '_') || 'pdf';
    for (const p of pages) {
        const padded = String(p.pageNumber).padStart(2, '0');
        zip.file(`${cleanBase}-page-${padded}.jpg`, p.blob);
    }
    const zipBlob = await zip.generateAsync({ type: 'blob' });
    downloadBlob(zipBlob, `${cleanBase}-jpgs.zip`);
}

window.FormnexaPdf = {
    renderPdfPagesToJpgs,
    downloadBlob,
    downloadJpgsZip,
};
