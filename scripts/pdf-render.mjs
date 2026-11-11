import fs from 'node:fs';
import path from 'node:path';
import { pathToFileURL } from 'node:url';
import { createCanvas } from '@napi-rs/canvas';
import * as pdfjsLib from 'pdfjs-dist/legacy/build/pdf.mjs';

const [, , pdfPathArg, outDirArg, scaleArg, qualityArg, maxPagesArg, singlePageArg] = process.argv;

if (!pdfPathArg || !outDirArg) {
    console.error('Usage: node pdf-render.mjs <pdfPath> <outDir> [scale] [quality] [maxPages] [singlePage]');
    process.exit(1);
}

const pdfPath = path.resolve(pdfPathArg);
const outDir = path.resolve(outDirArg);
const scale = Math.min(3.5, Math.max(0.5, Number(scaleArg || 2.0) || 2.0));
const quality = Math.min(100, Math.max(30, Math.round(Number(qualityArg || 90) || 90)));
const maxPages = Math.min(50, Math.max(1, Math.round(Number(maxPagesArg || 40) || 40)));
const singlePage = singlePageArg ? Math.max(1, Math.round(Number(singlePageArg))) : 0;

fs.mkdirSync(outDir, { recursive: true });

const pkgRoot = path.resolve(path.dirname(new URL(import.meta.url).pathname.replace(/^\/([A-Za-z]:)/, '$1')), '..');
const pdfjsRoot = path.join(pkgRoot, 'node_modules', 'pdfjs-dist');
const cMapUrl = pathToFileURL(path.join(pdfjsRoot, 'cmaps') + path.sep).href;
const standardFontDataUrl = pathToFileURL(path.join(pdfjsRoot, 'standard_fonts') + path.sep).href;

try {
    const data = new Uint8Array(fs.readFileSync(pdfPath));
    const loadingTask = pdfjsLib.getDocument({
        data,
        cMapUrl,
        cMapPacked: true,
        standardFontDataUrl,
        useSystemFonts: true,
        verbosity: 0,
    });
    const doc = await loadingTask.promise;

    const numPages = doc.numPages;
    const pagesToRender = [];

    if (singlePage > 0) {
        if (singlePage > numPages) {
            throw new Error(`Requested page ${singlePage} exceeds PDF page count (${numPages}).`);
        }
        pagesToRender.push(singlePage);
    } else {
        const limit = Math.min(numPages, maxPages);
        for (let p = 1; p <= limit; p++) {
            pagesToRender.push(p);
        }
    }

    const pages = [];

    for (const pageNum of pagesToRender) {
        const page = await doc.getPage(pageNum);
        const viewport = page.getViewport({ scale });
        const width = Math.max(1, Math.floor(viewport.width));
        const height = Math.max(1, Math.floor(viewport.height));

        const canvas = createCanvas(width, height);
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);

        await page.render({ canvasContext: ctx, viewport }).promise;

        const filename = `page-${String(pageNum).padStart(2, '0')}.jpg`;
        const outPath = path.join(outDir, filename);
        const jpegBuffer = canvas.toBuffer('image/jpeg', quality);
        fs.writeFileSync(outPath, jpegBuffer);

        pages.push({
            pageNumber: pageNum,
            filename,
            width,
            height,
        });
    }

    if (typeof doc.cleanup === 'function') {
        await doc.cleanup();
    }
    if (typeof loadingTask.destroy === 'function') {
        await loadingTask.destroy();
    }

    process.stdout.write(JSON.stringify({
        numPages,
        renderedCount: pages.length,
        pages,
    }));
} catch (err) {
    console.error(err?.message || String(err));
    process.exit(1);
}
