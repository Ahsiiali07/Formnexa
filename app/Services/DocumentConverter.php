<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpPresentation\IOFactory as PptIOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Shape\Drawing\File as PptDrawingFile;
use PhpOffice\PhpPresentation\Style\Alignment as PptAlignment;
use PhpOffice\PhpPresentation\Style\Color as PptColor;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory as SheetIOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment as SheetAlignment;
use PhpOffice\PhpSpreadsheet\Style\Fill as SheetFill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\PhpWord;
use Smalot\PdfParser\Parser as PdfParser;
use Symfony\Component\Process\Process;
use ZipArchive;

class DocumentConverter
{
    /**
     * Strip ASCII control characters invalid in XML 1.0 (keeps \t, \n, \r).
     */
    protected function cleanXmlText(string $text): string
    {
        $utf8 = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        return (string) preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $utf8);
    }

    /**
     * Derive clean download filename with target extension.
     */
    protected function outputFilename(?string $originalName, string $fallbackBase, string $ext): string
    {
        $base = $originalName ? pathinfo($originalName, PATHINFO_FILENAME) : $fallbackBase;
        $safe = trim((string) preg_replace('/[^A-Za-z0-9\-_ ]+/', '_', $base), ' _');
        return ($safe !== '' ? $safe : $fallbackBase) . '.' . ltrim($ext, '.');
    }

    /**
     * Load image via GD, auto-rotate from EXIF orientation if JPEG, and return JPEG bytes + dimensions.
     *
     * @return array{bytes: string, width: int, height: int}
     */
    protected function normalizeImageToJpeg(string $path, int $quality = 90): array
    {
        $info = @getimagesize($path);
        if (!$info) {
            throw new \RuntimeException('One of the uploaded files is not a valid image.');
        }

        $mime = $info['mime'] ?? '';
        $img = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            'image/gif' => @imagecreatefromgif($path),
            default => false,
        };

        if (!$img) {
            throw new \RuntimeException('Unsupported image format (' . $mime . '). Use JPG, PNG, or WebP.');
        }

        // Flatten transparent PNG/WebP onto white backdrop so JPEGs never turn black
        if (in_array($mime, ['image/png', 'image/webp', 'image/gif'], true)) {
            $w = imagesx($img);
            $h = imagesy($img);
            $canvas = imagecreatetruecolor($w, $h);
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
            imagecopy($canvas, $img, 0, 0, 0, 0, $w, $h);
            imagedestroy($img);
            $img = $canvas;
        }

        // Auto-fix smartphone EXIF orientation flags
        if (($mime === 'image/jpeg' || $mime === 'image/jpg') && function_exists('exif_read_data')) {
            $exif = @exif_read_data($path);
            $orient = (int) ($exif['Orientation'] ?? 1);
            $rotated = match ($orient) {
                3 => @imagerotate($img, 180, 0),
                6 => @imagerotate($img, -90, 0),
                8 => @imagerotate($img, 90, 0),
                default => false,
            };
            if ($rotated !== false) {
                imagedestroy($img);
                $img = $rotated;
            }
        }

        $w = imagesx($img);
        $h = imagesy($img);
        ob_start();
        imagejpeg($img, null, $quality);
        $bytes = (string) ob_get_clean();
        imagedestroy($img);

        return ['bytes' => $bytes, 'width' => $w, 'height' => $h];
    }

    /**
     * 1. JPG / PNG / WebP -> PDF
     *
     * @param array<int, UploadedFile> $files
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function imagesToPdf(array $files, array $options = []): array
    {
        if (empty($files)) {
            throw new \RuntimeException('Please upload at least one JPG, PNG, or WebP image.');
        }

        $pageSize = $options['page_size'] ?? 'a4';       // a4 | letter | fit
        $orientation = $options['orientation'] ?? 'auto'; // auto | portrait | landscape
        $marginKey = $options['margin'] ?? 'small';       // none | small | normal

        $marginPt = match ($marginKey) {
            'none' => 0,
            'normal' => 36,
            default => 16,
        };

        $normalized = [];
        foreach ($files as $file) {
            $img = $this->normalizeImageToJpeg($file->getRealPath(), 90);
            $normalized[] = [
                'dataUri' => 'data:image/jpeg;base64,' . base64_encode($img['bytes']),
                'w' => $img['width'],
                'h' => $img['height'],
            ];
        }

        // Determine paper size & orientation
        if ($pageSize === 'fit') {
            $first = $normalized[0];
            $maxDim = max($first['w'], $first['h'], 1);
            $scale = min(1.0, 1200 / $maxDim);
            $ptW = max(200, round($first['w'] * $scale * 0.75) + ($marginPt * 2));
            $ptH = max(200, round($first['h'] * $scale * 0.75) + ($marginPt * 2));
            $paper = [0, 0, $ptW, $ptH];
            $paperOrient = 'portrait';
        } else {
            if ($orientation === 'auto') {
                $landscapeVotes = count(array_filter($normalized, fn ($n) => $n['w'] > $n['h']));
                $paperOrient = ($landscapeVotes > count($normalized) / 2) ? 'landscape' : 'portrait';
            } else {
                $paperOrient = ($orientation === 'landscape') ? 'landscape' : 'portrait';
            }
            $paper = ($pageSize === 'letter') ? 'letter' : 'a4';
            $ptW = ($paper === 'letter' ? 612 : 595.28);
            $ptH = ($paper === 'letter' ? 792 : 841.89);
            if ($paperOrient === 'landscape') {
                [$ptW, $ptH] = [$ptH, $ptW];
            }
        }

        $innerW = max(80, $ptW - ($marginPt * 2));
        $innerH = max(80, $ptH - ($marginPt * 2));

        $htmlPages = [];
        $total = count($normalized);
        foreach ($normalized as $i => $item) {
            $breakCss = ($i < $total - 1) ? 'page-break-after: always;' : '';
            // Fit image accurately within printable frame preserving aspect ratio
            $ratio = min($innerW / max(1, $item['w']), $innerH / max(1, $item['h']));
            $drawW = round($item['w'] * $ratio, 2);
            $drawH = round($item['h'] * $ratio, 2);

            $htmlPages[] = '<div style="width:' . $ptW . 'pt; height:' . ($ptH - 1) . 'pt; margin:0; padding:0; text-align:center; ' . $breakCss . '">'
                . '<table style="width:100%; height:' . ($ptH - 2) . 'pt; border-collapse:collapse;"><tr>'
                . '<td align="center" valign="middle" style="padding:' . $marginPt . 'pt;">'
                . '<img src="' . $item['dataUri'] . '" style="width:' . $drawW . 'pt; height:' . $drawH . 'pt; display:block; margin:0 auto;">'
                . '</td></tr></table></div>';
        }

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>@page{margin:0;}body{margin:0;padding:0;background:#ffffff;}</style></head><body>'
            . implode('', $htmlPages)
            . '</body></html>';

        $pdfBytes = Pdf::loadHTML($html)->setPaper($paper, $paperOrient)->output();

        return [
            'bytes' => $pdfBytes,
            'filename' => $this->outputFilename($files[0]->getClientOriginalName(), 'images', 'pdf'),
            'mime' => 'application/pdf',
        ];
    }

    /**
     * 2. WORD (DOCX / RTF / ODT / TXT) -> PDF
     *
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function wordToPdf(UploadedFile $file, array $options = []): array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        if ($ext === 'doc') {
            $sig = file_get_contents($path, false, null, 0, 8) ?: '';
            if (str_starts_with($sig, 'PK')) {
                $ext = 'docx';
            } elseif (str_starts_with(ltrim($sig), '{')) {
                $ext = 'rtf';
            } elseif (stripos($sig, '<html') !== false || stripos($sig, '<!doc') !== false) {
                $ext = 'html';
            } else {
                throw new \RuntimeException('Legacy binary .doc format is not directly supported. Please save as .docx in Microsoft Word or Google Docs and try again.');
            }
        }

        if ($ext === 'docx') {
            $bodyHtml = $this->renderDocxOoxmlToHtml($path);
        } elseif ($ext === 'html' || $ext === 'htm') {
            $raw = file_get_contents($path) ?: '';
            return $this->htmlToPdf(null, $raw, '', $options);
        } elseif ($ext === 'txt' || $ext === 'md') {
            $txt = htmlspecialchars($this->cleanXmlText(file_get_contents($path) ?: ''), ENT_QUOTES, 'UTF-8');
            $bodyHtml = '<div style="white-space:pre-wrap; font-size:10.5pt; line-height:1.55;">' . $txt . '</div>';
        } else {
            // RTF / ODT via PhpWord
            $readerType = $ext === 'rtf' ? 'RTF' : 'ODText';
            try {
                $phpWord = WordIOFactory::load($path, $readerType);
                $tmpHtml = tempnam(sys_get_temp_dir(), 'fx_w_') . '.html';
                WordIOFactory::createWriter($phpWord, 'HTML')->save($tmpHtml);
                $rawHtml = file_get_contents($tmpHtml) ?: '';
                @unlink($tmpHtml);
                return $this->htmlToPdf(null, $rawHtml, '', $options);
            } catch (\Throwable $e) {
                throw new \RuntimeException('Could not read Word document: ' . $e->getMessage());
            }
        }

        $pageSize = ($options['page_size'] ?? 'a4') === 'letter' ? 'letter' : 'a4';
        $fullHtml = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>'
            . '@page { size: ' . $pageSize . ' portrait; margin: 20mm 18mm; }'
            . 'body { font-family: "DejaVu Sans", Helvetica, Arial, sans-serif; font-size: 10.5pt; line-height: 1.5; color: #1E293B; margin: 0; }'
            . 'h1 { font-size: 18pt; color: #0F172A; margin: 12pt 0 6pt 0; }'
            . 'h2 { font-size: 14pt; color: #0F172A; margin: 10pt 0 5pt 0; }'
            . 'h3 { font-size: 12pt; color: #1E293B; margin: 8pt 0 4pt 0; }'
            . 'p { margin: 0 0 6pt 0; }'
            . 'ul, ol { margin: 4pt 0 8pt 18pt; padding: 0; }'
            . 'li { margin-bottom: 3pt; }'
            . 'table { width: 100%; border-collapse: collapse; margin: 8pt 0 12pt 0; }'
            . 'td, th { border: 1px solid #CBD5E1; padding: 5pt 7pt; vertical-align: top; }'
            . 'img { max-width: 100%; height: auto; }'
            . '</style></head><body>' . $bodyHtml . '</body></html>';

        $pdfBytes = Pdf::loadHTML($fullHtml)->setPaper($pageSize, 'portrait')->output();

        return [
            'bytes' => $pdfBytes,
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'document', 'pdf'),
            'mime' => 'application/pdf',
        ];
    }

    /**
     * Parse OOXML .docx (paragraphs, inline formatting, headings, lists, tables, embedded media) cleanly into HTML.
     */
    protected function renderDocxOoxmlToHtml(string $zipPath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Could not open DOCX archive (file may be corrupted or encrypted).');
        }

        $docXml = $zip->getFromName('word/document.xml');
        if ($docXml === false) {
            $zip->close();
            throw new \RuntimeException('Invalid DOCX structure (word/document.xml missing).');
        }

        // Load media relationships
        $rels = [];
        $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
        if ($relsXml !== false) {
            $relDom = new DOMDocument();
            @$relDom->loadXML($relsXml);
            foreach ($relDom->getElementsByTagName('Relationship') as $rel) {
                $id = $rel->getAttribute('Id');
                $target = $rel->getAttribute('Target');
                if ($id && $target && !str_starts_with($target, 'http')) {
                    $entry = 'word/' . ltrim($target, '/');
                    $bin = $zip->getFromName($entry);
                    if ($bin !== false) {
                        $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                        $mime = match ($ext) {
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                            'webp' => 'image/webp',
                            default => 'image/jpeg',
                        };
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true)) {
                            $rels[$id] = 'data:' . $mime . ';base64,' . base64_encode($bin);
                        }
                    }
                }
            }
        }
        $zip->close();

        $dom = new DOMDocument();
        @$dom->loadXML($docXml);
        $xp = new DOMXPath($dom);
        $xp->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xp->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xp->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');

        $body = $xp->query('//w:body')->item(0);
        if (!$body) {
            return '';
        }

        $html = '';
        $inList = false;

        foreach ($body->childNodes as $node) {
            if (!($node instanceof DOMElement)) continue;

            if ($node->localName === 'p') {
                $pStyleNode = $xp->query('.//w:pPr/w:pStyle', $node)->item(0);
                $styleVal = $pStyleNode instanceof DOMElement ? $pStyleNode->getAttribute('w:val') : '';
                $isList = $xp->query('.//w:pPr/w:numPr', $node)->length > 0;
                $jcNode = $xp->query('.//w:pPr/w:jc', $node)->item(0);
                $align = $jcNode instanceof DOMElement ? $jcNode->getAttribute('w:val') : '';
                $alignCss = in_array($align, ['center', 'right', 'justify'], true) ? "text-align:{$align};" : '';

                $inner = $this->renderDocxRuns($xp, $node, $rels);

                if (trim(strip_tags($inner)) === '' && !str_contains($inner, '<img')) {
                    if ($inList) {
                        $html .= '</ul>';
                        $inList = false;
                    }
                    $html .= '<p style="height:6pt;"></p>';
                    continue;
                }

                if ($isList) {
                    if (!$inList) {
                        $html .= '<ul>';
                        $inList = true;
                    }
                    $html .= '<li style="' . $alignCss . '">' . $inner . '</li>';
                    continue;
                }

                if ($inList) {
                    $html .= '</ul>';
                    $inList = false;
                }

                if (preg_match('/Heading1|Title/i', $styleVal)) {
                    $html .= '<h1 style="' . $alignCss . '">' . $inner . '</h1>';
                } elseif (preg_match('/Heading2|Subtitle/i', $styleVal)) {
                    $html .= '<h2 style="' . $alignCss . '">' . $inner . '</h2>';
                } elseif (preg_match('/Heading[3-6]/i', $styleVal)) {
                    $html .= '<h3 style="' . $alignCss . '">' . $inner . '</h3>';
                } else {
                    $html .= '<p style="' . $alignCss . '">' . $inner . '</p>';
                }
            } elseif ($node->localName === 'tbl') {
                if ($inList) {
                    $html .= '</ul>';
                    $inList = false;
                }
                $html .= '<table>';
                foreach ($xp->query('./w:tr', $node) as $rowIdx => $tr) {
                    $html .= '<tr>';
                    foreach ($xp->query('./w:tc', $tr) as $tc) {
                        $shdNode = $xp->query('.//w:tcPr/w:shd', $tc)->item(0);
                        $fill = ($shdNode instanceof DOMElement) ? $shdNode->getAttribute('w:fill') : '';
                        $bgCss = ($fill && $fill !== 'auto' && preg_match('/^[0-9A-Fa-f]{6}$/', $fill))
                            ? 'background-color:#' . $fill . ';'
                            : ($rowIdx === 0 ? 'background-color:#F8FAFC; font-weight:bold;' : '');
                        $cellHtml = '';
                        foreach ($xp->query('./w:p', $tc) as $cp) {
                            $cellHtml .= '<div>' . $this->renderDocxRuns($xp, $cp, $rels) . '</div>';
                        }
                        $html .= '<td style="' . $bgCss . '">' . ($cellHtml !== '' ? $cellHtml : '&nbsp;') . '</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';
            }
        }

        if ($inList) {
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * Render formatted runs & embedded pictures inside a DOCX paragraph.
     */
    protected function renderDocxRuns(DOMXPath $xp, DOMElement $pNode, array $rels): string
    {
        $out = '';
        foreach ($xp->query('.//w:r', $pNode) as $run) {
            if (!($run instanceof DOMElement)) continue;

            // Check embedded image
            foreach ($xp->query('.//a:blip', $run) as $blip) {
                if ($blip instanceof DOMElement) {
                    $rId = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed')
                        ?: $blip->getAttribute('r:embed');
                    if ($rId && isset($rels[$rId])) {
                        $out .= '<img src="' . $rels[$rId] . '" style="max-width:460pt; max-height:380pt; margin:4pt 0;">';
                    }
                }
            }

            $styles = [];
            $isBold = $xp->query('./w:rPr/w:b', $run)->length > 0;
            $isItalic = $xp->query('./w:rPr/w:i', $run)->length > 0;
            $isUnderline = $xp->query('./w:rPr/w:u', $run)->length > 0;
            $isStrike = $xp->query('./w:rPr/w:strike', $run)->length > 0;

            $colorNode = $xp->query('./w:rPr/w:color', $run)->item(0);
            if ($colorNode instanceof DOMElement) {
                $hex = $colorNode->getAttribute('w:val');
                if ($hex && $hex !== 'auto' && preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
                    $styles[] = 'color:#' . $hex;
                }
            }

            $szNode = $xp->query('./w:rPr/w:sz', $run)->item(0);
            if ($szNode instanceof DOMElement && is_numeric($szNode->getAttribute('w:val'))) {
                $pt = max(7, min(36, ((float) $szNode->getAttribute('w:val')) / 2));
                $styles[] = 'font-size:' . $pt . 'pt';
            }

            if ($isBold) $styles[] = 'font-weight:bold';
            if ($isItalic) $styles[] = 'font-style:italic';
            if ($isUnderline) $styles[] = 'text-decoration:underline';
            if ($isStrike) $styles[] = 'text-decoration:line-through';

            $runText = '';
            foreach ($run->childNodes as $rc) {
                if ($rc instanceof DOMElement) {
                    if ($rc->localName === 't') {
                        $runText .= htmlspecialchars($rc->textContent, ENT_QUOTES, 'UTF-8');
                    } elseif ($rc->localName === 'tab') {
                        $runText .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                    } elseif ($rc->localName === 'br') {
                        $runText .= '<br>';
                    }
                }
            }

            if ($runText !== '') {
                $out .= !empty($styles)
                    ? '<span style="' . implode(';', $styles) . ';">' . $runText . '</span>'
                    : $runText;
            }
        }
        return $out;
    }

    /**
     * 3. POWERPOINT (PPTX) -> PDF
     *
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function powerpointToPdf(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('Could not open PPTX presentation file.');
        }

        // Order slides via ppt/presentation.xml + presentation.xml.rels
        $slideEntries = [];
        $presXml = $zip->getFromName('ppt/presentation.xml');
        $presRelsXml = $zip->getFromName('ppt/_rels/presentation.xml.rels');

        if ($presXml !== false && $presRelsXml !== false) {
            $relMap = [];
            $relDom = new DOMDocument();
            @$relDom->loadXML($presRelsXml);
            foreach ($relDom->getElementsByTagName('Relationship') as $rel) {
                $relMap[$rel->getAttribute('Id')] = $rel->getAttribute('Target');
            }

            $presDom = new DOMDocument();
            @$presDom->loadXML($presXml);
            foreach ($presDom->getElementsByTagName('sldId') as $sldId) {
                $rId = $sldId->getAttribute('r:id');
                if ($rId && isset($relMap[$rId])) {
                    $target = ltrim($relMap[$rId], '/');
                    $slideEntries[] = str_starts_with($target, 'ppt/') ? $target : 'ppt/' . $target;
                }
            }
        }

        if (empty($slideEntries)) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('/^ppt\/slides\/slide\d+\.xml$/i', $name)) {
                    $slideEntries[] = $name;
                }
            }
            natsort($slideEntries);
            $slideEntries = array_values($slideEntries);
        }

        if (empty($slideEntries)) {
            $zip->close();
            throw new \RuntimeException('No slides found in the uploaded PPTX file.');
        }

        $slideHtmlBlocks = [];
        $total = count($slideEntries);

        foreach ($slideEntries as $sIdx => $slidePath) {
            $xml = $zip->getFromName($slidePath);
            if ($xml === false) continue;

            // Load slide-level media rels
            $slideBase = basename($slidePath);
            $relsPath = 'ppt/slides/_rels/' . $slideBase . '.rels';
            $media = [];
            $sRelsXml = $zip->getFromName($relsPath);
            if ($sRelsXml !== false) {
                $sRelDom = new DOMDocument();
                @$sRelDom->loadXML($sRelsXml);
                foreach ($sRelDom->getElementsByTagName('Relationship') as $rel) {
                    $target = $rel->getAttribute('Target');
                    if (str_contains($target, 'media/')) {
                        $mediaEntry = 'ppt/' . ltrim(str_replace('../', '', $target), '/');
                        $bin = $zip->getFromName($mediaEntry);
                        if ($bin !== false) {
                            $ext = strtolower(pathinfo($mediaEntry, PATHINFO_EXTENSION));
                            if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif'], true)) {
                                $mime = $ext === 'png' ? 'image/png' : ($ext === 'gif' ? 'image/gif' : 'image/jpeg');
                                $media[$rel->getAttribute('Id')] = 'data:' . $mime . ';base64,' . base64_encode($bin);
                            }
                        }
                    }
                }
            }

            $sDom = new DOMDocument();
            @$sDom->loadXML($xml);
            $xp = new DOMXPath($sDom);
            $xp->registerNamespace('p', 'http://schemas.openxmlformats.org/presentationml/2006/main');
            $xp->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

            // Detect slide solid background color if present
            $bgColor = '#FFFFFF';
            $bgRgb = $xp->query('//p:bg//a:srgbClr')->item(0);
            if ($bgRgb instanceof DOMElement && preg_match('/^[0-9A-Fa-f]{6}$/', $bgRgb->getAttribute('val'))) {
                $bgColor = '#' . $bgRgb->getAttribute('val');
            }
            $isDarkBg = $this->isDarkHex($bgColor);
            $fgColor = $isDarkBg ? '#F8FAFC' : '#0F172A';
            $subFg = $isDarkBg ? '#CBD5E1' : '#334155';

            $titles = [];
            $bullets = [];
            $tables = [];
            $images = [];

            foreach ($xp->query('//p:sp') as $sp) {
                $ph = $xp->query('.//p:ph', $sp)->item(0);
                $phType = ($ph instanceof DOMElement) ? $ph->getAttribute('type') : '';
                $isTitle = in_array($phType, ['title', 'ctrTitle'], true);

                foreach ($xp->query('.//a:p', $sp) as $ap) {
                    $line = trim($ap->textContent);
                    if ($line === '') continue;
                    if ($isTitle) {
                        $titles[] = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
                    } else {
                        $pPr = $xp->query('./a:pPr', $ap)->item(0);
                        $lvl = ($pPr instanceof DOMElement && $pPr->hasAttribute('lvl')) ? (int) $pPr->getAttribute('lvl') : 0;
                        $bullets[] = ['text' => htmlspecialchars($line, ENT_QUOTES, 'UTF-8'), 'lvl' => $lvl];
                    }
                }
            }

            foreach ($xp->query('//a:tbl') as $tbl) {
                $tHtml = '<table style="width:100%; border-collapse:collapse; margin-top:10pt; font-size:9.5pt;">';
                foreach ($xp->query('./a:tr', $tbl) as $rIdx => $tr) {
                    $tHtml .= '<tr>';
                    foreach ($xp->query('./a:tc', $tr) as $tc) {
                        $bg = $rIdx === 0 ? 'background-color:#E2E8F0; font-weight:bold; color:#0F172A;' : 'color:' . $subFg . ';';
                        $tHtml .= '<td style="border:1px solid #94A3B8; padding:5pt 8pt; ' . $bg . '">'
                            . htmlspecialchars(trim($tc->textContent), ENT_QUOTES, 'UTF-8') . '</td>';
                    }
                    $tHtml .= '</tr>';
                }
                $tHtml .= '</table>';
                $tables[] = $tHtml;
            }

            foreach ($xp->query('//p:pic//a:blip') as $blip) {
                if ($blip instanceof DOMElement) {
                    $rId = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed')
                        ?: $blip->getAttribute('r:embed');
                    if ($rId && isset($media[$rId])) {
                        $images[] = '<img src="' . $media[$rId] . '" style="max-width:340pt; max-height:240pt; border-radius:4pt; margin:4pt;">';
                    }
                }
            }

            $breakCss = ($sIdx < $total - 1) ? 'page-break-after: always;' : '';
            $titleHtml = !empty($titles)
                ? '<h1 style="font-size:21pt; font-weight:bold; color:' . $fgColor . '; border-bottom:2px solid ' . ($isDarkBg ? '#38BDF8' : '#2563EB') . '; padding-bottom:8pt; margin:0 0 14pt 0;">' . implode(' — ', $titles) . '</h1>'
                : '';

            $bodyHtml = '';
            if (!empty($bullets)) {
                $bodyHtml .= '<ul style="margin:0; padding-left:18pt; color:' . $subFg . '; font-size:12pt; line-height:1.55;">';
                foreach ($bullets as $b) {
                    $padLeft = ($b['lvl'] * 16) . 'pt';
                    $bodyHtml .= '<li style="margin-bottom:6pt; margin-left:' . $padLeft . ';">' . $b['text'] . '</li>';
                }
                $bodyHtml .= '</ul>';
            }
            $bodyHtml .= implode('', $tables);

            $contentLayout = !empty($images)
                ? '<table style="width:100%; border-collapse:collapse;"><tr>'
                    . '<td valign="top" style="width:58%; padding-right:16pt;">' . $bodyHtml . '</td>'
                    . '<td valign="top" align="right" style="width:42%;">' . implode('<br>', $images) . '</td>'
                    . '</tr></table>'
                : $bodyHtml;

            $slideHtmlBlocks[] = '<div style="width:100%; height:555pt; background-color:' . $bgColor . '; padding:30pt 38pt; box-sizing:border-box; position:relative; ' . $breakCss . '">'
                . $titleHtml
                . $contentLayout
                . '<div style="position:absolute; bottom:16pt; right:30pt; font-size:8.5pt; color:#94A3B8;">Slide ' . ($sIdx + 1) . ' / ' . $total . '</div>'
                . '</div>';
        }

        $zip->close();

        $fullHtml = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>'
            . '@page { size: a4 landscape; margin: 0; }'
            . 'body { margin: 0; padding: 0; font-family: "DejaVu Sans", Helvetica, Arial, sans-serif; }'
            . '</style></head><body>' . implode('', $slideHtmlBlocks) . '</body></html>';

        return [
            'bytes' => Pdf::loadHTML($fullHtml)->setPaper('a4', 'landscape')->output(),
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'presentation', 'pdf'),
            'mime' => 'application/pdf',
        ];
    }

    protected function isDarkHex(string $hex): bool
    {
        $h = ltrim($hex, '#');
        if (strlen($h) !== 6) return false;
        $r = hexdec(substr($h, 0, 2));
        $g = hexdec(substr($h, 2, 2));
        $b = hexdec(substr($h, 4, 2));
        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128;
    }

    /**
     * 4. EXCEL / CSV / ODS -> PDF
     *
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function excelToPdf(UploadedFile $file, array $options = []): array
    {
        $path = $file->getRealPath();
        $orient = ($options['orientation'] ?? 'landscape') === 'portrait' ? 'portrait' : 'landscape';
        $pageSize = ($options['page_size'] ?? 'a4') === 'letter' ? 'letter' : 'a4';
        $gridlines = ($options['gridlines'] ?? true) ? true : false;

        try {
            $spreadsheet = SheetIOFactory::load($path);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Could not parse spreadsheet file: ' . $e->getMessage());
        }

        $sheetBlocks = [];
        $sheetCount = $spreadsheet->getSheetCount();

        foreach ($spreadsheet->getAllSheets() as $sIdx => $sheet) {
            $highestRow = min(500, $sheet->getHighestDataRow());
            $highestColIndex = min(26, Coordinate::columnIndexFromString($sheet->getHighestDataColumn()));

            if ($highestRow < 1 || $highestColIndex < 1) continue;

            // Map merged cell regions
            $skipCells = [];
            $spanCells = [];
            foreach ($sheet->getMergeCells() as $mergeRange) {
                [$start, $end] = explode(':', $mergeRange);
                preg_match('/^([A-Z]+)(\d+)$/', $start, $sm);
                preg_match('/^([A-Z]+)(\d+)$/', $end, $em);
                if (!empty($sm) && !empty($em)) {
                    $c1 = Coordinate::columnIndexFromString($sm[1]);
                    $r1 = (int) $sm[2];
                    $c2 = Coordinate::columnIndexFromString($em[1]);
                    $r2 = (int) $em[2];
                    $spanCells["{$r1}:{$c1}"] = ['colspan' => max(1, $c2 - $c1 + 1), 'rowspan' => max(1, $r2 - $r1 + 1)];
                    for ($r = $r1; $r <= $r2; $r++) {
                        for ($c = $c1; $c <= $c2; $c++) {
                            if ($r !== $r1 || $c !== $c1) {
                                $skipCells["{$r}:{$c}"] = true;
                            }
                        }
                    }
                }
            }

            $fontSize = $highestColIndex > 12 ? '6.8pt' : ($highestColIndex > 7 ? '7.8pt' : '8.8pt');
            $borderCss = $gridlines ? 'border:1px solid #CBD5E1;' : 'border-bottom:1px solid #E2E8F0;';

            $tbl = '<div style="' . ($sIdx < $sheetCount - 1 ? 'page-break-after:always;' : '') . '">'
                . '<div style="font-size:11pt; font-weight:bold; color:#0F172A; margin-bottom:8pt; padding-bottom:4pt; border-bottom:2px solid #059669;">'
                . htmlspecialchars($sheet->getTitle(), ENT_QUOTES, 'UTF-8') . '</div>'
                . '<table style="width:100%; border-collapse:collapse; font-size:' . $fontSize . ';">';

            for ($r = 1; $r <= $highestRow; $r++) {
                $tbl .= '<tr>';
                for ($c = 1; $c <= $highestColIndex; $c++) {
                    if (isset($skipCells["{$r}:{$c}"])) continue;

                    $coord = Coordinate::stringFromColumnIndex($c) . $r;
                    $cell = $sheet->getCell($coord);
                    $val = $cell->getFormattedValue();
                    $cellStyle = $sheet->getStyle($coord);

                    $tdStyle = $borderCss . 'padding:3.5pt 5pt; vertical-align:middle;';
                    if ($cellStyle->getFont()->getBold() || $r === 1) {
                        $tdStyle .= 'font-weight:bold;';
                    }
                    $fillType = $cellStyle->getFill()->getFillType();
                    if ($fillType === SheetFill::FILL_SOLID) {
                        $rgb = $cellStyle->getFill()->getStartColor()->getRGB();
                        if ($rgb && strtoupper($rgb) !== 'FFFFFF' && strtoupper($rgb) !== '000000') {
                            $tdStyle .= 'background-color:#' . $rgb . ';';
                        }
                    } elseif ($r === 1) {
                        $tdStyle .= 'background-color:#F1F5F9; color:#0F172A;';
                    }

                    $hAlign = $cellStyle->getAlignment()->getHorizontal();
                    if ($hAlign === SheetAlignment::HORIZONTAL_RIGHT || (is_numeric($cell->getValue()) && $hAlign === SheetAlignment::HORIZONTAL_GENERAL)) {
                        $tdStyle .= 'text-align:right;';
                    } elseif ($hAlign === SheetAlignment::HORIZONTAL_CENTER) {
                        $tdStyle .= 'text-align:center;';
                    }

                    $attrs = '';
                    if (isset($spanCells["{$r}:{$c}"])) {
                        $sp = $spanCells["{$r}:{$c}"];
                        if ($sp['colspan'] > 1) $attrs .= ' colspan="' . $sp['colspan'] . '"';
                        if ($sp['rowspan'] > 1) $attrs .= ' rowspan="' . $sp['rowspan'] . '"';
                    }

                    $tbl .= '<td' . $attrs . ' style="' . $tdStyle . '">'
                        . ($val !== '' ? htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') : '&nbsp;')
                        . '</td>';
                }
                $tbl .= '</tr>';
            }
            $tbl .= '</table></div>';
            $sheetBlocks[] = $tbl;
        }

        if (empty($sheetBlocks)) {
            throw new \RuntimeException('The uploaded spreadsheet contains no readable data rows.');
        }

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>'
            . '@page { size: ' . $pageSize . ' ' . $orient . '; margin: 12mm; }'
            . 'body { font-family: "DejaVu Sans", Helvetica, Arial, sans-serif; color: #1E293B; margin: 0; }'
            . '</style></head><body>' . implode('', $sheetBlocks) . '</body></html>';

        return [
            'bytes' => Pdf::loadHTML($html)->setPaper($pageSize, $orient)->output(),
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'spreadsheet', 'pdf'),
            'mime' => 'application/pdf',
        ];
    }

    /**
     * 5. HTML (File / Markup / Safe Public URL) -> PDF
     *
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function htmlToPdf(?UploadedFile $file, string $rawHtml = '', string $url = '', array $options = []): array
    {
        $pageSize = ($options['page_size'] ?? 'a4') === 'letter' ? 'Letter' : 'A4';
        $orient = ($options['orientation'] ?? 'portrait') === 'landscape' ? 'landscape' : 'portrait';
        $baseName = 'webpage';

        if ($file !== null) {
            $html = (string) file_get_contents($file->getRealPath());
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: 'document';
        } elseif (trim($url) !== '') {
            $html = $this->fetchSafePublicHtml(trim($url));
            $host = parse_url(trim($url), PHP_URL_HOST) ?: 'webpage';
            $baseName = preg_replace('/[^A-Za-z0-9\-_]+/', '_', $host) ?: 'webpage';
        } else {
            $html = $rawHtml;
        }

        if (trim($html) === '') {
            throw new \RuntimeException('Please upload an HTML file, paste HTML code, or enter a valid public https:// URL.');
        }

        // Security: sanitize scripts, inline handlers, and local file:// paths before rendering
        $safeHtml = $this->sanitizeHtmlForPdf($html);
        if (stripos($safeHtml, '<html') === false) {
            $safeHtml = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:Arial,sans-serif;color:#1e293b;padding:24px;line-height:1.55;}</style></head><body>' . $safeHtml . '</body></html>';
        }

        $pageRule = '<style>@page { size: ' . $pageSize . ' ' . $orient . '; margin: 14mm; }</style>';
        $safeHtml = str_ireplace('</head>', $pageRule . '</head>', $safeHtml);
        if (stripos($safeHtml, $pageRule) === false) {
            $safeHtml = $pageRule . $safeHtml;
        }

        // Prefer local headless Chrome for full Flexbox/Grid CSS support, fallback cleanly to DomPDF
        $chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
        if (file_exists($chromePath)) {
            $tmpHtml = tempnam(sys_get_temp_dir(), 'fx_h_') . '.html';
            $tmpPdf = tempnam(sys_get_temp_dir(), 'fx_p_') . '.pdf';
            $tmpProfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fx_chrome_' . uniqid();
            try {
                file_put_contents($tmpHtml, $safeHtml);
                $fileUri = 'file:///' . str_replace('\\', '/', $tmpHtml);
                $cmd = sprintf(
                    '"%s" --headless=new --disable-gpu --no-pdf-header-footer --user-data-dir="%s" --print-to-pdf="%s" "%s" 2>&1',
                    $chromePath,
                    $tmpProfile,
                    $tmpPdf,
                    $fileUri
                );
                @exec($cmd, $out, $code);
                if (file_exists($tmpPdf) && filesize($tmpPdf) > 500) {
                    $bytes = (string) file_get_contents($tmpPdf);
                    if (str_starts_with($bytes, '%PDF-')) {
                        return [
                            'bytes' => $bytes,
                            'filename' => $this->outputFilename($baseName, 'document', 'pdf'),
                            'mime' => 'application/pdf',
                        ];
                    }
                }
            } finally {
                @unlink($tmpHtml);
                @unlink($tmpPdf);
                $this->deleteDirectory($tmpProfile);
            }
        }

        $bytes = Pdf::loadHTML($safeHtml)->setPaper(strtolower($pageSize), $orient)->output();
        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($baseName, 'document', 'pdf'),
            'mime' => 'application/pdf',
        ];
    }

    /**
     * Fetch public HTML URL with SSRF IP-range verification.
     */
    protected function fetchSafePublicHtml(string $url): string
    {
        $parts = parse_url($url);
        $scheme = strtolower($parts['scheme'] ?? '');
        $host = $parts['host'] ?? '';

        if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
            throw new \RuntimeException('Please enter a valid http:// or https:// URL.');
        }

        $ips = @gethostbynamel($host) ?: [];
        if (empty($ips)) {
            throw new \RuntimeException('Could not resolve hostname: ' . $host);
        }
        foreach ($ips as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new \RuntimeException('Fetching local or private network URLs is blocked for security.');
            }
        }

        $ctx = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 8,
                'max_redirects' => 2,
                'user_agent' => 'FormnexaConverter/1.0',
            ],
        ]);
        $html = @file_get_contents($url, false, $ctx, 0, 2 * 1024 * 1024);
        if ($html === false || trim($html) === '') {
            throw new \RuntimeException('Could not fetch webpage HTML content.');
        }
        return $html;
    }

    protected function sanitizeHtmlForPdf(string $html): string
    {
        $html = (string) preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
        $html = (string) preg_replace('/<iframe\b[^>]*>[\s\S]*?<\/iframe>/i', '', $html);
        $html = (string) preg_replace('/\son[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = (string) preg_replace('/file:\/\/\/?[^\s"\'<>)]+/i', '#blocked', $html);
        return $html;
    }

    protected function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') continue;
            $p = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($p) ? $this->deleteDirectory($p) : @unlink($p);
        }
        @rmdir($dir);
    }

    /**
     * Extract ordered page lines from PDF using smalot/pdfparser coordinate transform when present.
     *
     * @return array<int, array<int, array{text: string, isHeading: bool, isBullet: bool}>>
     */
    protected function extractPdfStructuredPages(string $pdfPath): array
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($pdfPath);
        $pages = $pdf->getPages();
        $result = [];

        foreach ($pages as $page) {
            $linesOut = [];
            $rows = $this->extractRowsFromPageCoordinates($page);

            if (!empty($rows)) {
                foreach ($rows as $rowCells) {
                    $line = trim(implode(' ', $rowCells));
                    if ($line === '') continue;
                    $isBullet = (bool) preg_match('/^[•●▪▫▸►\-\*]\s+/u', $line);
                    $clean = trim((string) preg_replace('/^[•●▪▫▸►\-\*]\s+/u', '', $line));
                    $isHeading = !$isBullet && mb_strlen($clean) <= 65 && (
                        strtoupper($clean) === $clean && preg_match('/[A-Z]/', $clean)
                    );
                    $linesOut[] = ['text' => $clean, 'isHeading' => $isHeading, 'isBullet' => $isBullet];
                }
            } else {
                $rawText = str_replace(["\r\n", "\r"], "\n", $page->getText());
                foreach (explode("\n", $rawText) as $rawLine) {
                    $line = trim((string) preg_replace('/\s+/u', ' ', $rawLine));
                    if ($line === '') continue;
                    $isBullet = (bool) preg_match('/^[•●▪▫▸►\-\*]\s+/u', $line);
                    $clean = trim((string) preg_replace('/^[•●▪▫▸►\-\*]\s+/u', '', $line));
                    $isHeading = !$isBullet && mb_strlen($clean) <= 65 && (
                        strtoupper($clean) === $clean && preg_match('/[A-Z]/', $clean)
                    );
                    $linesOut[] = ['text' => $clean, 'isHeading' => $isBullet ? false : $isHeading, 'isBullet' => $isBullet];
                }
            }
            $result[] = $linesOut;
        }

        return $result;
    }

    /**
     * Cluster text tokens on a PDF page into visual rows & columns using TM coordinates.
     *
     * @return array<int, array<int, array{x: float, text: string}>>|array<int, array<int, string>>
     */
    protected function extractRowsFromPageCoordinates($page, bool $withX = false): array
    {
        try {
            $dataTm = $page->getDataTm();
        } catch (\Throwable $e) {
            return [];
        }
        if (empty($dataTm) || !is_array($dataTm)) {
            return [];
        }

        $tokens = [];
        foreach ($dataTm as $item) {
            if (!is_array($item) || count($item) < 2) continue;
            $tm = $item[0];
            $str = trim((string) preg_replace('/\s+/u', ' ', $this->cleanXmlText((string) $item[1])));
            if ($str === '' || !is_array($tm) || count($tm) < 6) continue;
            $tokens[] = [
                'x' => (float) $tm[4],
                'y' => (float) $tm[5],
                'text' => $str,
            ];
        }

        if (empty($tokens)) return [];

        // Sort top-to-bottom (higher Y first in PDF coordinates), then left-to-right (lower X first)
        usort($tokens, static function ($a, $b) {
            if (abs($a['y'] - $b['y']) > 3.5) {
                return $b['y'] <=> $a['y'];
            }
            return $a['x'] <=> $b['x'];
        });

        $rows = [];
        $currentRow = [];
        $currentY = $tokens[0]['y'];

        foreach ($tokens as $tok) {
            if (abs($tok['y'] - $currentY) > 4.0) {
                if (!empty($currentRow)) {
                    usort($currentRow, fn ($a, $b) => $a['x'] <=> $b['x']);
                    $rows[] = $currentRow;
                }
                $currentRow = [$tok];
                $currentY = $tok['y'];
            } else {
                $currentRow[] = $tok;
            }
        }
        if (!empty($currentRow)) {
            usort($currentRow, fn ($a, $b) => $a['x'] <=> $b['x']);
            $rows[] = $currentRow;
        }

        if ($withX) {
            return $rows;
        }

        // Collapse X tokens within row into clean string chunks
        $textRows = [];
        foreach ($rows as $row) {
            $chunks = [];
            foreach ($row as $t) {
                $chunks[] = $t['text'];
            }
            $textRows[] = $chunks;
        }
        return $textRows;
    }

    /**
     * 7. PDF -> WORD (DOCX)
     *
     * @param array<int, string> $pageImages Base64 data URLs rendered from browser PDF.js (used in visual mode)
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function pdfToWord(UploadedFile $file, array $pageImages = [], array $options = []): array
    {
        $mode = $options['mode'] ?? 'editable'; // editable | visual
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 15, 'color' => '0F172A'], ['spaceAfter' => 120]);

        $tmpFiles = [];
        try {
            if ($mode === 'visual' && empty($pageImages)) {
                $pageImages = $this->tryRenderPdfDataUrls($file->getRealPath(), ['scale' => 1.75, 'quality' => 88, 'max_pages' => 25]);
            }

            if ($mode === 'visual' && !empty($pageImages)) {
                foreach ($pageImages as $idx => $imgData) {
                    $bin = $this->decodeDataUrlImage($imgData);
                    if (!$bin) continue;
                    $tmpImg = tempnam(sys_get_temp_dir(), 'fx_wi_') . '.jpg';
                    file_put_contents($tmpImg, $bin);
                    $tmpFiles[] = $tmpImg;

                    $section = $phpWord->addSection(['marginTop' => 360, 'marginBottom' => 360, 'marginLeft' => 420, 'marginRight' => 420]);
                    $section->addImage($tmpImg, ['width' => 500, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            } else {
                $pages = $this->extractPdfStructuredPages($file->getRealPath());
                $hasAnyText = false;
                foreach ($pages as $lines) {
                    if (!empty($lines)) {
                        $hasAnyText = true;
                        break;
                    }
                }

                // If scanned PDF with zero text, rasterize server-side or use client frames cleanly instead of blank DOCX!
                if (!$hasAnyText && empty($pageImages)) {
                    $pageImages = $this->tryRenderPdfDataUrls($file->getRealPath(), ['scale' => 1.75, 'quality' => 88, 'max_pages' => 25]);
                }
                if (!$hasAnyText && !empty($pageImages)) {
                    return $this->pdfToWord($file, $pageImages, ['mode' => 'visual']);
                }

                if (!$hasAnyText) {
                    throw new \RuntimeException('No selectable text found in this PDF. Try Visual Layout mode to embed scanned pages cleanly inside DOCX.');
                }

                foreach ($pages as $pIdx => $lines) {
                    $section = $phpWord->addSection(['marginTop' => 900, 'marginBottom' => 900, 'marginLeft' => 1000, 'marginRight' => 1000]);
                    foreach ($lines as $lineIdx => $item) {
                        $safeTxt = $this->cleanXmlText($item['text']);
                        if ($safeTxt === '') continue;
                        if ($item['isHeading'] || $lineIdx === 0) {
                            $section->addText($safeTxt, ['bold' => true, 'size' => $lineIdx === 0 ? 15 : 12, 'color' => '0F172A'], ['spaceAfter' => 90]);
                        } elseif ($item['isBullet']) {
                            $section->addListItem($safeTxt, 0, ['size' => 10.5, 'color' => '334155'], null, ['spaceAfter' => 60]);
                        } else {
                            $section->addText($safeTxt, ['size' => 10.5, 'color' => '1E293B'], ['spaceAfter' => 80]);
                        }
                    }
                }
            }

            $tmpOut = tempnam(sys_get_temp_dir(), 'fx_doc_') . '.docx';
            $tmpFiles[] = $tmpOut;
            WordIOFactory::createWriter($phpWord, 'Word2007')->save($tmpOut);
            $bytes = (string) file_get_contents($tmpOut);
        } finally {
            foreach ($tmpFiles as $tf) {
                @unlink($tf);
            }
        }

        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'converted', 'docx'),
            'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
    }

    /**
     * 8. PDF -> POWERPOINT (PPTX)
     *
     * @param array<int, string> $pageImages Base64 data URLs rendered from PDF.js
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function pdfToPowerpoint(UploadedFile $file, array $pageImages = [], array $options = []): array
    {
        $mode = $options['mode'] ?? (!empty($pageImages) ? 'visual' : 'editable');
        $pres = new PhpPresentation();
        $pres->getLayout()->setDocumentLayout(\PhpOffice\PhpPresentation\DocumentLayout::LAYOUT_SCREEN_16X9, true);
        // Remove initial blank slide cleanly before creating ours
        $pres->removeSlideByIndex(0);

        $tmpFiles = [];
        try {
            $pagesText = $this->extractPdfStructuredPages($file->getRealPath());

            if ($mode === 'visual' && empty($pageImages)) {
                $pageImages = $this->tryRenderPdfDataUrls($file->getRealPath(), ['scale' => 1.75, 'quality' => 88, 'max_pages' => 30]);
            }

            if ($mode === 'visual' && !empty($pageImages)) {
                foreach ($pageImages as $idx => $imgData) {
                    $bin = $this->decodeDataUrlImage($imgData);
                    if (!$bin) continue;
                    $tmpImg = tempnam(sys_get_temp_dir(), 'fx_si_') . '.jpg';
                    file_put_contents($tmpImg, $bin);
                    $tmpFiles[] = $tmpImg;

                    [$imgW, $imgH] = @getimagesize($tmpImg) ?: [960, 540];
                    $slide = $pres->createSlide();
                    // 16:9 slide canvas = 960 x 540 px
                    $ratio = min(960 / max(1, $imgW), 540 / max(1, $imgH));
                    $drawW = (int) round($imgW * $ratio);
                    $drawH = (int) round($imgH * $ratio);

                    $shape = new PptDrawingFile();
                    $shape->setName('PDF Page ' . ($idx + 1))
                        ->setPath($tmpImg)
                        ->setWidth($drawW)
                        ->setHeight($drawH)
                        ->setOffsetX((int) round((960 - $drawW) / 2))
                        ->setOffsetY((int) round((540 - $drawH) / 2));
                    $slide->addShape($shape);
                }
            } else {
                if (empty($pagesText)) {
                    throw new \RuntimeException('No extractable content found in this PDF.');
                }
                foreach ($pagesText as $idx => $lines) {
                    $slide = $pres->createSlide();
                    $titleText = !empty($lines) ? $this->cleanXmlText($lines[0]['text']) : ('Slide ' . ($idx + 1));
                    $bodyLines = array_slice($lines, 1, 14);

                    $titleShape = $slide->createRichTextShape()
                        ->setHeight(70)
                        ->setWidth(860)
                        ->setOffsetX(50)
                        ->setOffsetY(36);
                    $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(PptAlignment::HORIZONTAL_LEFT);
                    $tr = $titleShape->createTextRun($titleText);
                    $tr->getFont()->setBold(true)->setSize(24)->setColor(new PptColor('FF0F172A'));

                    $bodyShape = $slide->createRichTextShape()
                        ->setHeight(390)
                        ->setWidth(860)
                        ->setOffsetX(50)
                        ->setOffsetY(120);
                    foreach ($bodyLines as $bIdx => $bItem) {
                        $txt = $this->cleanXmlText($bItem['text']);
                        if ($txt === '') continue;
                        $p = $bIdx === 0 ? $bodyShape->getActiveParagraph() : $bodyShape->createParagraph();
                        $p->getAlignment()->setHorizontal(PptAlignment::HORIZONTAL_LEFT);
                        $run = $p->createTextRun(($bItem['isBullet'] ? '• ' : '') . $txt);
                        $run->getFont()->setSize(14)->setColor(new PptColor('FF334155'));
                    }
                }
            }

            if ($pres->getSlideCount() === 0) {
                $pres->createSlide();
            }

            $tmpOut = tempnam(sys_get_temp_dir(), 'fx_ppt_') . '.pptx';
            $tmpFiles[] = $tmpOut;
            PptIOFactory::createWriter($pres, 'PowerPoint2007')->save($tmpOut);
            $bytes = (string) file_get_contents($tmpOut);
        } finally {
            foreach ($tmpFiles as $tf) {
                @unlink($tf);
            }
        }

        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'presentation', 'pptx'),
            'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
    }

    /**
     * 9. PDF -> EXCEL (XLSX Table Extractor)
     *
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function pdfToExcel(UploadedFile $file, array $options = []): array
    {
        $mergeMode = $options['merge_sheets'] ?? 'per_page'; // per_page | single_sheet
        $parser = new PdfParser();
        $pdf = $parser->parseFile($file->getRealPath());
        $pages = $pdf->getPages();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $combinedSheet = null;
        $combinedRow = 1;
        if ($mergeMode === 'single_sheet') {
            $combinedSheet = $spreadsheet->createSheet();
            $combinedSheet->setTitle('Extracted Tables');
        }

        $totalRowsAdded = 0;

        foreach ($pages as $pIdx => $page) {
            $coordRows = $this->extractRowsFromPageCoordinates($page, true);
            $tableMatrix = [];

            if (!empty($coordRows)) {
                // Cluster X coordinates across the page to align table columns accurately
                $allX = [];
                foreach ($coordRows as $r) {
                    foreach ($r as $tok) {
                        $allX[] = $tok['x'];
                    }
                }
                sort($allX);
                $colAnchors = [];
                foreach ($allX as $x) {
                    if (empty($colAnchors) || ($x - end($colAnchors)) > 22.0) {
                        $colAnchors[] = $x;
                    }
                }
                if (count($colAnchors) > 18) {
                    $colAnchors = array_slice($colAnchors, 0, 18);
                }

                foreach ($coordRows as $r) {
                    $rowCells = array_fill(0, max(1, count($colAnchors)), '');
                    foreach ($r as $tok) {
                        // Find closest anchor <= or nearest within 18pt
                        $bestCol = 0;
                        $bestDist = PHP_FLOAT_MAX;
                        foreach ($colAnchors as $cIdx => $anchorX) {
                            $d = abs($tok['x'] - $anchorX);
                            if ($d < $bestDist) {
                                $bestDist = $d;
                                $bestCol = $cIdx;
                            }
                        }
                        $rowCells[$bestCol] = trim($rowCells[$bestCol] . ' ' . $tok['text']);
                    }
                    // Trim trailing empty cells
                    while (!empty($rowCells) && end($rowCells) === '') {
                        array_pop($rowCells);
                    }
                    if (!empty($rowCells)) {
                        $tableMatrix[] = $rowCells;
                    }
                }
            } else {
                // Fallback line/space split
                foreach (explode("\n", str_replace(["\r\n", "\r"], "\n", $page->getText())) as $line) {
                    $trimmed = trim($line);
                    if ($trimmed === '') continue;
                    $cols = preg_split('/\t|\s{2,}/u', $trimmed) ?: [$trimmed];
                    $tableMatrix[] = array_map('trim', $cols);
                }
            }

            if (empty($tableMatrix)) continue;

            if ($mergeMode === 'single_sheet') {
                $targetSheet = $combinedSheet;
                $startRow = $combinedRow;
            } else {
                $targetSheet = $spreadsheet->createSheet();
                $targetSheet->setTitle('Page ' . ($pIdx + 1));
                $startRow = 1;
            }

            foreach ($tableMatrix as $rOffset => $cells) {
                $rNum = $startRow + $rOffset;
                foreach ($cells as $cIdx => $val) {
                    $coord = Coordinate::stringFromColumnIndex($cIdx + 1) . $rNum;
                    $numericProbe = str_replace([',', '$', '€', '£', ' '], '', $val);
                    if ($val !== '' && is_numeric($numericProbe) && !str_starts_with($val, '0') && !str_contains($val, '-')) {
                        $targetSheet->setCellValue($coord, (float) $numericProbe);
                    } else {
                        $targetSheet->setCellValue($coord, $this->cleanXmlText($val));
                    }
                    $totalRowsAdded++;
                }
                if ($rNum === 1 || ($mergeMode === 'per_page' && $rOffset === 0)) {
                    $lastCol = Coordinate::stringFromColumnIndex(max(1, count($cells)));
                    $targetSheet->getStyle("A{$rNum}:{$lastCol}{$rNum}")->getFont()->setBold(true);
                    $targetSheet->getStyle("A{$rNum}:{$lastCol}{$rNum}")->getFill()
                        ->setFillType(SheetFill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFF1F5F9');
                }
            }

            if ($mergeMode === 'single_sheet') {
                $combinedRow += count($tableMatrix) + 1;
            }
        }

        if ($spreadsheet->getSheetCount() === 0 || $totalRowsAdded === 0) {
            throw new \RuntimeException('No extractable text or table rows found in this PDF.');
        }

        foreach ($spreadsheet->getAllSheets() as $s) {
            $maxCol = Coordinate::columnIndexFromString($s->getHighestDataColumn());
            for ($c = 1; $c <= $maxCol; $c++) {
                $s->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
            }
        }

        $tmpOut = tempnam(sys_get_temp_dir(), 'fx_xls_') . '.xlsx';
        try {
            (new Xlsx($spreadsheet))->save($tmpOut);
            $bytes = (string) file_get_contents($tmpOut);
        } finally {
            @unlink($tmpOut);
        }

        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'tables', 'xlsx'),
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
    }

    /**
     * 10. PDF -> Archival PDF/A-1b / PDF/A-3b via TCPDF native PDFA mode
     *
     * @param array<int, string> $pageImages High-DPI rendered page data URLs
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function pdfToPdfA(UploadedFile $file, array $pageImages = [], array $options = []): array
    {
        $fontDir = storage_path('app/tcpdf-fonts');
        if (!defined('K_PATH_FONTS') && is_dir($fontDir)) {
            define('K_PATH_FONTS', rtrim($fontDir, '/\\') . DIRECTORY_SEPARATOR);
        }

        $conformance = ($options['conformance'] ?? '1b') === '3b' ? 3 : 1;
        $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: 'Archival Document';

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, $conformance);
        $pdf->setCreator('Formnexa PDF/A Converter');
        $pdf->setAuthor('Formnexa');
        $pdf->setTitle($title);
        $pdf->setSubject('ISO 19005 Archival PDF/A-' . ($conformance === 3 ? '3B' : '1B'));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setFont('dejavusans', '', 10);
        $pdf->setMargins(0, 0, 0);
        $pdf->setAutoPageBreak(false, 0);

        $addedPages = 0;

        if (empty($pageImages)) {
            $pageImages = $this->tryRenderPdfDataUrls($file->getRealPath(), ['scale' => 1.75, 'quality' => 88, 'max_pages' => 25]);
        }

        if (!empty($pageImages)) {
            foreach ($pageImages as $imgData) {
                $bin = $this->decodeDataUrlImage($imgData);
                if (!$bin) continue;
                $size = @getimagesizefromstring($bin);
                if (!$size) continue;

                // Calculate physical mm dimensions matching image aspect (anchored cleanly around A4 width/height)
                $pxW = max(1, $size[0]);
                $pxH = max(1, $size[1]);
                $isLandscape = $pxW > $pxH;
                $pageW = $isLandscape ? 297.0 : 210.0;
                $pageH = round($pageW * ($pxH / $pxW), 2);
                if ($pageH > 420.0) {
                    $pageH = 297.0;
                    $pageW = round($pageH * ($pxW / $pxH), 2);
                }

                $pdf->AddPage($isLandscape ? 'L' : 'P', [$pageW, $pageH]);
                $pdf->Image('@' . $bin, 0, 0, $pageW, $pageH, 'JPEG', '', '', false, 300, '', false, false, 0);
                $addedPages++;
            }
        }

        if ($addedPages === 0) {
            // Vector fallback from parsed PDF text if no client frames provided
            $pagesText = $this->extractPdfStructuredPages($file->getRealPath());
            $pdf->setMargins(16, 16, 16);
            $pdf->setAutoPageBreak(true, 16);
            $pdf->setFont('dejavusans', '', 10);

            foreach ($pagesText as $lines) {
                $pdf->AddPage('P', 'A4');
                $html = '';
                foreach ($lines as $idx => $item) {
                    $t = htmlspecialchars($this->cleanXmlText($item['text']), ENT_QUOTES, 'UTF-8');
                    if ($t === '') continue;
                    $html .= $item['isHeading'] || $idx === 0
                        ? '<h3 style="margin:4pt 0;">' . $t . '</h3>'
                        : '<p style="margin:2pt 0;">' . $t . '</p>';
                }
                if ($html === '') {
                    $html = '<p>&nbsp;</p>';
                }
                $pdf->writeHTML($html, true, false, true, false, '');
                $addedPages++;
            }
        }

        if ($addedPages === 0) {
            $pdf->AddPage('P', 'A4');
        }

        $bytes = $pdf->Output('', 'S');

        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'archival', 'pdf'),
            'mime' => 'application/pdf',
        ];
    }

    /**
     * Optional server-side ZIP packer for rendered JPG page buffers.
     *
     * @param array<int, string> $pageImages
     * @return array{bytes: string, filename: string, mime: string}
     */
    public function imagesToZip(array $pageImages, string $baseName = 'pdf-pages'): array
    {
        if (empty($pageImages)) {
            throw new \RuntimeException('No rendered pages received.');
        }

        $tmpZip = tempnam(sys_get_temp_dir(), 'fx_z_') . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to build ZIP archive.');
        }

        $cleanBase = pathinfo($baseName, PATHINFO_FILENAME) ?: 'page';
        $i = 1;
        foreach ($pageImages as $imgData) {
            $bin = $this->decodeDataUrlImage($imgData);
            if ($bin) {
                $zip->addFromString(sprintf('%s-page-%02d.jpg', $cleanBase, $i), $bin);
                $i++;
            }
        }
        $zip->close();

        $bytes = (string) file_get_contents($tmpZip);
        @unlink($tmpZip);

        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($baseName, 'pdf-pages', 'zip'),
            'mime' => 'application/zip',
        ];
    }

    /**
     * 6. PDF -> JPG (Server-side rasterization for mobile/API uploads, or client-rendered ZIP packing)
     *
     * @param array<int, mixed> $pageFrames
     * @return array<string, mixed>
     */
    public function pdfToJpg(?UploadedFile $file, array $pageFrames = [], array $options = []): array
    {
        if (!empty($pageFrames)) {
            return $this->imagesToZip($pageFrames, (string) ($options['base_name'] ?? 'pdf-pages'));
        }

        if ($file === null) {
            throw new \RuntimeException('Please upload a PDF file to convert to JPG.');
        }

        $output = strtolower((string) ($options['output'] ?? 'zip'));
        $singlePage = isset($options['page']) && (int) $options['page'] > 0 ? (int) $options['page'] : 0;
        if ($output === 'jpg' && $singlePage === 0) {
            $singlePage = 1;
        }

        $rendered = $this->renderPdfToJpgPages($file->getRealPath(), [
            'scale' => $options['scale'] ?? 2.0,
            'quality' => $options['quality'] ?? 90,
            'max_pages' => $options['max_pages'] ?? 40,
            'page' => $singlePage,
        ]);

        $pages = $rendered['pages'];
        if (empty($pages)) {
            throw new \RuntimeException('No pages could be rendered from this PDF.');
        }

        if ($output === 'json') {
            return [
                'mode' => 'json',
                'num_pages' => $rendered['num_pages'],
                'rendered_count' => count($pages),
                'pages' => array_map(static fn (array $p) => [
                    'page_number' => $p['page_number'],
                    'filename' => $p['filename'],
                    'width' => $p['width'],
                    'height' => $p['height'],
                    'mime' => 'image/jpeg',
                    'size' => strlen($p['bytes']),
                    'data_uri' => 'data:image/jpeg;base64,' . base64_encode($p['bytes']),
                    'base64' => base64_encode($p['bytes']),
                ], $pages),
            ];
        }

        if ($output === 'jpg' || ($output === 'auto' && count($pages) === 1)) {
            $first = $pages[0];
            $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: 'document';
            $pageName = sprintf('%s-page-%02d.jpg', preg_replace('/[^A-Za-z0-9\-_]+/', '_', $base) ?: 'document', $first['page_number']);

            return [
                'bytes' => $first['bytes'],
                'filename' => $pageName,
                'mime' => 'image/jpeg',
            ];
        }

        $tmpZip = tempnam(sys_get_temp_dir(), 'fx_z_') . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to build ZIP archive.');
        }

        $cleanBase = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: 'pdf-pages';
        $cleanBase = trim((string) preg_replace('/[^A-Za-z0-9\-_ ]+/', '_', $cleanBase), ' _') ?: 'pdf-pages';
        foreach ($pages as $p) {
            $zip->addFromString(sprintf('%s-page-%02d.jpg', $cleanBase, $p['page_number']), $p['bytes']);
        }
        $zip->close();

        $bytes = (string) file_get_contents($tmpZip);
        @unlink($tmpZip);

        return [
            'bytes' => $bytes,
            'filename' => $this->outputFilename($file->getClientOriginalName(), 'pdf-pages', 'zip'),
            'mime' => 'application/zip',
        ];
    }

    /**
     * Render PDF pages on the server to JPEG bytes using Node + pdfjs-dist + @napi-rs/canvas.
     *
     * @return array{num_pages: int, pages: array<int, array{page_number: int, filename: string, width: int, height: int, bytes: string}>}
     */
    public function renderPdfToJpgPages(string $pdfPath, array $options = []): array
    {
        $nodeBin = $this->findNodeBinary();
        $scriptPath = base_path('scripts/pdf-render.mjs');
        if ($nodeBin === null || !is_file($scriptPath)) {
            throw new \RuntimeException('Server PDF renderer is unavailable (Node.js or pdf-render script missing).');
        }

        $scale = (string) min(3.5, max(0.5, (float) ($options['scale'] ?? 2.0)));
        $quality = (string) min(100, max(30, (int) ($options['quality'] ?? 90)));
        $maxPages = (string) min(50, max(1, (int) ($options['max_pages'] ?? 40)));
        $singlePage = (string) max(0, (int) ($options['page'] ?? 0));

        $outDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fx_pdf_' . uniqid('', true);
        @mkdir($outDir, 0775, true);

        try {
            $args = [$nodeBin, $scriptPath, $pdfPath, $outDir, $scale, $quality, $maxPages];
            if ((int) $singlePage > 0) {
                $args[] = $singlePage;
            }

            $process = new Process($args, base_path());
            $process->setTimeout(90);
            $process->run();

            if (!$process->isSuccessful()) {
                $err = trim($process->getErrorOutput() ?: $process->getOutput());
                throw new \RuntimeException('Failed to render PDF pages: ' . ($err ?: 'Unknown renderer error.'));
            }

            $meta = json_decode($process->getOutput(), true);
            if (!is_array($meta) || empty($meta['pages'])) {
                throw new \RuntimeException('PDF renderer returned no pages.');
            }

            $pages = [];
            foreach ($meta['pages'] as $item) {
                $imgPath = $outDir . DIRECTORY_SEPARATOR . ($item['filename'] ?? '');
                if (!is_file($imgPath)) {
                    continue;
                }
                $pages[] = [
                    'page_number' => (int) $item['pageNumber'],
                    'filename' => (string) $item['filename'],
                    'width' => (int) $item['width'],
                    'height' => (int) $item['height'],
                    'bytes' => (string) file_get_contents($imgPath),
                ];
            }

            return [
                'num_pages' => (int) ($meta['numPages'] ?? count($pages)),
                'pages' => $pages,
            ];
        } finally {
            $this->deleteDirectory($outDir);
        }
    }

    /**
     * Best-effort server-side PDF page rasterization returning data URLs (for visual DOCX/PPTX/PDF-A fallback).
     *
     * @return array<int, string>
     */
    protected function tryRenderPdfDataUrls(string $pdfPath, array $options = []): array
    {
        try {
            $rendered = $this->renderPdfToJpgPages($pdfPath, $options);
            return array_map(
                static fn (array $p) => 'data:image/jpeg;base64,' . base64_encode($p['bytes']),
                $rendered['pages']
            );
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function findNodeBinary(): ?string
    {
        $configured = env('NODE_BINARY');
        if (is_string($configured) && $configured !== '' && is_file($configured)) {
            return $configured;
        }

        $candidates = DIRECTORY_SEPARATOR === '\\'
            ? ['C:\\Program Files\\nodejs\\node.exe', 'C:\\Program Files (x86)\\nodejs\\node.exe']
            : ['/usr/local/bin/node', '/usr/bin/node'];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        $probe = new Process([DIRECTORY_SEPARATOR === '\\' ? 'where' : 'which', 'node']);
        $probe->run();
        if ($probe->isSuccessful()) {
            $first = trim(explode("\n", str_replace("\r", '', $probe->getOutput()))[0] ?? '');
            if ($first !== '' && is_file($first)) {
                return $first;
            }
        }

        return null;
    }

    protected function decodeDataUrlImage(mixed $input): ?string
    {
        if ($input instanceof UploadedFile) {
            $path = $input->getRealPath();
            if ($path && is_file($path)) {
                return $this->normalizeImageToJpeg($path, 88)['bytes'];
            }
            return null;
        }
        if (!is_string($input)) return null;
        if (preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,(.+)$/is', trim($input), $m)) {
            $raw = base64_decode($m[2], true);
            if ($raw === false) return null;
            if (strtolower($m[1]) !== 'jpeg' && strtolower($m[1]) !== 'jpg') {
                $img = @imagecreatefromstring($raw);
                if (!$img) return null;
                ob_start();
                imagejpeg($img, null, 92);
                $raw = (string) ob_get_clean();
                imagedestroy($img);
            }
            return $raw;
        }
        return null;
    }
}
