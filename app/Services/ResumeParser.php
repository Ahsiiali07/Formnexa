<?php

namespace App\Services;

use App\Support\ResumeCatalog;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser as PdfParser;
use ZipArchive;

class ResumeParser
{
    /**
     * Extract plain UTF-8 text from an uploaded PDF, DOCX, TXT, or JSON resume file.
     *
     * @throws \RuntimeException when the file cannot be read or parsed
     */
    public function extractTextFromFile(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        if ($ext === 'pdf') {
            try {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText();
                if (trim($text) === '') {
                    throw new \RuntimeException('No selectable text found in this PDF (it may be a scanned image). Please paste your CV text instead.');
                }
                return $text;
            } catch (\RuntimeException $e) {
                throw $e;
            } catch (\Throwable $e) {
                throw new \RuntimeException('Could not read PDF file: ' . $e->getMessage());
            }
        }

        if ($ext === 'docx') {
            return $this->extractDocxText($path);
        }

        if (in_array($ext, ['txt', 'md', 'rtf', 'csv', 'json'], true)) {
            $raw = file_get_contents($path);
            if ($raw === false) {
                throw new \RuntimeException('Unable to read uploaded text file.');
            }
            return $raw;
        }

        throw new \RuntimeException('Unsupported file format. Please upload PDF, DOCX, TXT, or JSON (or paste your CV text directly).');
    }

    /**
     * Extract structured paragraphs from a .docx archive via ZipArchive + XML.
     */
    protected function extractDocxText(string $path): string
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('Could not open DOCX document.');
        }

        $xmlContent = '';
        foreach (['word/header1.xml', 'word/document.xml'] as $part) {
            $content = $zip->getFromName($part);
            if ($content !== false) {
                $xmlContent .= "\n" . $content;
            }
        }
        $zip->close();

        if (trim($xmlContent) === '') {
            throw new \RuntimeException('The uploaded DOCX file appears empty.');
        }

        $xmlContent = preg_replace('/<w:tab\s*\/>/i', '  ', $xmlContent);
        $xmlContent = preg_replace('/<\/w:p>/i', "\n", $xmlContent);
        $xmlContent = preg_replace('/<\/w:tr>/i', "\n", $xmlContent);
        $text = strip_tags($xmlContent);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');

        return trim($text);
    }

    /**
     * UTF-8 safe punctuation & whitespace trimmer.
     */
    protected function trimPunct(string $str): string
    {
        return (string) preg_replace('/^[\s,|()\-\*\x{2013}\x{2014}\x{2022}\x{25CF}\x{25AA}]+|[\s,|()\-\*\x{2013}\x{2014}\x{2022}\x{25CF}\x{25AA}]+$/u', '', $str);
    }

    /**
     * Normalize ALL-CAPS names/titles cleanly while preserving common acronyms.
     */
    protected function smartCase(string $str): string
    {
        $str = trim($str);
        if ($str === '') return '';

        // Only normalize if mostly uppercase alphabetic characters
        $letters = preg_replace('/[^A-Za-z]/', '', $str) ?? '';
        if ($letters !== '' && $letters === strtoupper($letters) && strlen($letters) > 3) {
            $acronyms = ['CEO', 'CTO', 'COO', 'CFO', 'CMO', 'VP', 'SVP', 'EVP', 'IT', 'HR', 'AI', 'ML', 'UI', 'UX', 'UK', 'USA', 'US', 'QA', 'PM', 'TPM', 'SQL', 'MBA', 'CFA', 'CPA', 'MD', 'PHD', 'BS', 'MS', 'BA', 'MA'];
            $words = preg_split('/(\s+)/', strtolower($str), -1, PREG_SPLIT_DELIM_CAPTURE) ?: [];
            foreach ($words as &$w) {
                $upper = strtoupper($w);
                if (in_array($upper, $acronyms, true)) {
                    $w = $upper;
                } elseif (preg_match('/^[a-z]/', $w)) {
                    $w = ucfirst($w);
                }
            }
            return implode('', $words);
        }

        return $str;
    }

    /**
     * Parse raw resume text (or JSON resume) into Formnexa's structured resume content array.
     */
    public function parse(string $rawInput): array
    {
        $rawInput = mb_convert_encoding($rawInput, 'UTF-8', 'UTF-8');
        $trimmed = trim($rawInput);

        if (str_starts_with($trimmed, '{')) {
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                return $this->parseJsonPayload($decoded);
            }
        }

        // Normalize encoding & line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $trimmed);
        $text = preg_replace('/\x{00A0}/u', ' ', $text);
        $rawLines = explode("\n", $text);
        $lines = array_values(array_filter(
            array_map(fn ($l) => trim((string) preg_replace('/\s+/u', ' ', $l)), $rawLines),
            fn ($l) => $l !== '' && !preg_match('/^references available upon?\/?\s*on request\.?$/i', $l)
        ));

        $result = ResumeCatalog::blankContent();
        // Start list sections strictly empty so unpresent sections are not populated by default stubs
        $result['experience'] = [];
        $result['education'] = [];
        $result['skills'] = [];
        $result['languages'] = [];
        $result['awards'] = [];
        $result['projects'] = [];

        // 1. Extract contact fields globally first (email, phone, linkedin, clean website)
        if (preg_match('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $text, $m)) {
            $result['personal']['email'] = strtolower($m[0]);
        }

        if (preg_match('/(\+?\d[\d\s\-\.\(\)]{7,18}\d)/', $text, $m)) {
            $candidate = trim($m[1]);
            if (!preg_match('/^(19|20)\d{2}\s*[-–]\s*(19|20)\d{2}$/', $candidate)
                && !preg_match('/^\d{2}[-\/]\d{2}[-\/]\d{4}$/', $candidate) // exclude DOB dates like 28-10-1994
                && strlen(preg_replace('/\D/', '', $candidate)) >= 8) {
                $result['personal']['phone'] = $candidate;
            }
        }

        if (preg_match('/(?:https?:\/\/)?(?:www\.)?linkedin\.com\/in\/[A-Za-z0-9\-_%]+/i', $text, $m)) {
            $result['personal']['linkedin'] = preg_replace('/^https?:\/\/(www\.)?/i', '', $m[0]);
        }

        // Website extraction: guard strictly against matching inside email addresses (e.g. gmail.com)
        if (preg_match_all('/(?:https?:\/\/|www\.)?(github\.com\/[A-Za-z0-9\-_]+|behance\.net\/[A-Za-z0-9\-_]+|dribbble\.com\/[A-Za-z0-9\-_]+|[a-z0-9][a-z0-9\-]{1,}\.(?:com|dev|io|design|org|net|ai|app|tech|me|co)(?:\/[^\s,|]*)?)/iu', $text, $matches, PREG_OFFSET_CAPTURE)) {
            $blockedDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'live.com', 'icloud.com', 'aol.com', 'protonmail.com', 'proton.me', 'linkedin.com'];
            foreach ($matches[0] as [$rawMatch, $offset]) {
                $prevChar = $offset > 0 ? substr($text, $offset - 1, 1) : '';
                if ($prevChar === '@' || preg_match('/[A-Za-z0-9._%+\-]$/', $prevChar)) {
                    continue;
                }
                $url = preg_replace('/^https?:\/\/(www\.)?/i', '', trim($rawMatch, ".,;:) "));
                $host = strtolower(explode('/', $url)[0]);
                if (in_array($host, $blockedDomains, true) || str_contains($url, '@')) {
                    continue;
                }
                $result['personal']['website'] = $url;
                break;
            }
        }

        // 2. Partition document into header + named sections (preserving two-column detection)
        $partitioned = $this->splitIntoSections($lines);
        $sections = $partitioned['sections'];
        $preSummarySection = $partitioned['pre_summary_section'];

        // 3. Parse header & contact_block (name, job_title, location from ADDRESS label)
        $headerAndContact = array_merge($sections['header'] ?? [], $sections['contact_block'] ?? []);
        $this->parseHeaderLines($headerAndContact, $result['personal']);

        // Handle two-column PDFs where left sidebar came first and full Name + Title sit at end of Personal / preceding section before Profile
        $this->recoverTwoColumnMasthead($sections, $preSummarySection, $result['personal']);

        if (!empty($result['personal']['full_name'])) {
            $result['personal']['full_name'] = $this->smartCase($result['personal']['full_name']);
        }
        if (!empty($result['personal']['job_title'])) {
            $result['personal']['job_title'] = $this->smartCase($result['personal']['job_title']);
        }

        // 4. Parse Summary / Profile
        if (!empty($sections['summary'])) {
            $result['summary'] = implode(' ', $sections['summary']);
        }

        // 5. Parse Work Experience + Earlier Experience
        $expEntries = !empty($sections['experience'])
            ? $this->parseExperienceSection($sections['experience'])
            : [];
        if (!empty($sections['earlier_experience'])) {
            $earlier = $this->parseEarlierExperienceSection($sections['earlier_experience']);
            $expEntries = array_merge($expEntries, $earlier);
        }
        $result['experience'] = $expEntries;

        // 6. Parse Education
        if (!empty($sections['education'])) {
            $result['education'] = $this->parseEducationSection($sections['education']);
        }

        // 7. Parse Skills
        if (!empty($sections['skills'])) {
            $result['skills'] = $this->parseSkillsSection($sections['skills']);
        }

        // 8. Parse Languages
        if (!empty($sections['languages'])) {
            $result['languages'] = $this->parseLanguagesSection($sections['languages']);
        }

        // 9. Parse Awards / Certifications
        if (!empty($sections['awards'])) {
            $result['awards'] = $this->parseAwardsSection($sections['awards']);
        }

        // 10. Parse Projects
        if (!empty($sections['projects'])) {
            $result['projects'] = $this->parseProjectsSection($sections['projects']);
        }

        // Ensure section visibility strictly reflects extracted non-empty sections
        $result['section_visibility'] = [
            'summary' => trim($result['summary']) !== '',
            'experience' => count(array_filter($result['experience'], fn ($e) => trim($e['role'] . $e['company'] . $e['bullets_text']) !== '')) > 0,
            'education' => count(array_filter($result['education'], fn ($e) => trim($e['degree'] . $e['school']) !== '')) > 0,
            'skills' => count($result['skills']) > 0,
            'languages' => count($result['languages']) > 0,
            'awards' => count($result['awards']) > 0,
            'projects' => count($result['projects']) > 0,
        ];

        // Keep at least one empty slot so the accordion form renders cleanly
        if (empty($result['experience'])) {
            $result['experience'] = ResumeCatalog::blankContent()['experience'];
        }
        if (empty($result['education'])) {
            $result['education'] = ResumeCatalog::blankContent()['education'];
        }

        return $result;
    }

    /**
     * Split normalized CV lines into sections by matching standard heading labels,
     * including auxiliary blocks (contact_block, personal_meta, references, earlier_experience)
     * so personal details never spill into skills/education on two-column PDFs.
     */
    protected function splitIntoSections(array $lines): array
    {
        $map = [
            'contact_block' => '/^(contact|contact me|contact info|contact information|contact details|reach out)$/i',
            'personal_meta' => '/^(personal|personal details|personal information|biodata|bio data)$/i',
            'summary' => '/^(profile|summary|professional summary|executive summary|career summary|professional profile|about me|about|career objective|objective)$/i',
            'experience' => '/^(work experience|professional experience|experience|employment|employment history|work history|career history|positions held)$/i',
            'earlier_experience' => '/^(earlier experience|previous experience|prior experience|other experience|additional experience)$/i',
            'education' => '/^(education|academic background|academics|educational background|qualifications|education & training)$/i',
            'skills' => '/^(skills|core skills|technical skills|professional skills|skills & expertise|relevant skills|core competencies|competencies|key skills|technologies|tech stack|areas of expertise|toolbox & craft)$/i',
            'languages' => '/^(languages|language proficiency|spoken languages)$/i',
            'awards' => '/^(awards|honors|awards & certifications|certifications & awards|awards and certifications|certifications|licenses & certifications|certificates|achievements|recognition)$/i',
            'projects' => '/^(projects|selected projects|key projects|personal projects|portfolio|featured portfolio|selected works)$/i',
            'references' => '/^(references|referees)$/i',
        ];

        $sections = ['header' => []];
        $current = 'header';
        $preSummarySection = null;

        foreach ($lines as $line) {
            $cleanHeading = trim((string) preg_replace('/^[\W_]+|[:\-–—_]+$/u', '', $line));
            $matchedSection = null;

            if (mb_strlen($cleanHeading) <= 45) {
                foreach ($map as $key => $regex) {
                    if (preg_match($regex, $cleanHeading)) {
                        $matchedSection = $key;
                        break;
                    }
                }
            }

            if ($matchedSection !== null) {
                if ($matchedSection === 'summary' && $preSummarySection === null && $current !== 'header') {
                    $preSummarySection = $current;
                }
                $current = $matchedSection;
                if (!isset($sections[$current])) {
                    $sections[$current] = [];
                }
                continue;
            }

            $sections[$current][] = $line;
        }

        return [
            'sections' => $sections,
            'pre_summary_section' => $preSummarySection,
        ];
    }

    /**
     * Parse top header & contact_block lines, skipping avatar monograms (1-3 uppercase initials)
     * and reading labeled lines like PHONE / EMAIL / ADDRESS.
     */
    protected function parseHeaderLines(array $headerLines, array &$personal): void
    {
        $cleanNonContact = [];
        $pendingLabel = null;
        $labelRegex = '/^(phone|mobile|tel|telephone|cell|email|e-mail|mail|address|location|city|residence|website|portfolio|linkedin)[:\s]*$/i';

        foreach ($headerLines as $line) {
            if (preg_match($labelRegex, $line, $lm)) {
                $pendingLabel = strtolower($lm[1]);
                continue;
            }

            // Inline label like "Address: Madina Garden, Lahore"
            if (preg_match('/^(address|location|residence)\s*:\s*(.+)$/i', $line, $am)) {
                if (empty($personal['location'])) {
                    $personal['location'] = trim($am[2]);
                }
                $pendingLabel = null;
                continue;
            }

            if ($pendingLabel !== null) {
                $lbl = $pendingLabel;
                $pendingLabel = null;
                if (in_array($lbl, ['address', 'location', 'city', 'residence'], true)) {
                    if (empty($personal['location'])) {
                        $personal['location'] = $line;
                    }
                    continue;
                }
                if (in_array($lbl, ['phone', 'mobile', 'tel', 'telephone', 'cell', 'email', 'e-mail', 'mail', 'linkedin', 'website', 'portfolio'], true)) {
                    continue;
                }
            }

            // Skip short avatar badge initials like "FA", "BW"
            if (preg_match('/^[A-Z]{1,3}$/', $line)) {
                continue;
            }

            $parts = preg_split('/\s*[|•·]\s*/u', $line) ?: [];
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part === '' || preg_match('/^[A-Z]{1,3}$/', $part)) continue;

                if (str_contains($part, '@') || preg_match('/linkedin\.com|github\.com|https?:\/\//i', $part)) {
                    continue;
                }
                if (preg_match('/^\+?[\d\s\-\.\(\)]{8,}$/', $part)) {
                    continue;
                }

                if (empty($personal['location'])
                    && preg_match('/^[A-Z][a-zA-Z\s\.]+,\s*[A-Z]{2,}(?:\s+\d{4,6})?$|^[A-Z][a-zA-Z\s]+,\s*[A-Z][a-zA-Z\s]+$/', $part)
                    && str_word_count($part) <= 5) {
                    $personal['location'] = $part;
                    continue;
                }

                $cleanNonContact[] = $part;
            }
        }

        if (!empty($cleanNonContact[0])) {
            $personal['full_name'] = mb_substr($cleanNonContact[0], 0, 80);
        }
        if (!empty($cleanNonContact[1])) {
            $personal['job_title'] = mb_substr($cleanNonContact[1], 0, 100);
        }
    }

    /**
     * When two-column PDFs serialize the sidebar before the main column, recover the real candidate
     * Name + Job Title sitting directly before PROFILE / SUMMARY inside personal_meta or the prior section.
     */
    protected function recoverTwoColumnMasthead(array &$sections, ?string $preSummarySection, array &$personal): void
    {
        if (empty($preSummarySection) || empty($sections[$preSummarySection])) {
            return;
        }

        $metaLabelRegex = '/^(father\'?s?\s*name|mother\'?s?\s*name|date of birth|dob|marital status|nationality|cnic|nic|passport|gender|sex|religion|domicile|age|languages?)[:\s]*$/i';
        $buf = &$sections[$preSummarySection];

        // If the section directly before PROFILE is personal_meta, strip known Label + Value pairs from top,
        // and any trailing lines right above PROFILE are the right column masthead!
        if ($preSummarySection === 'personal_meta') {
            $lastMetaValIdx = -1;
            foreach ($buf as $i => $l) {
                if (preg_match($metaLabelRegex, $l)) {
                    $lastMetaValIdx = min(count($buf) - 1, $i + 1);
                } elseif (preg_match('/^(father|date of birth|dob|marital|nationality|cnic|gender|religion|domicile)\b.*:/i', $l)) {
                    $lastMetaValIdx = $i;
                }
            }
            $trailing = array_values(array_slice($buf, $lastMetaValIdx + 1));
            $this->applyMastheadCandidateLines($trailing, $personal);
            return;
        }

        // Also recover if personal['full_name'] is still empty (e.g. only avatar monogram was in header)
        if (trim((string) ($personal['full_name'] ?? '')) === '' && count($buf) >= 2) {
            $tail = array_slice($buf, -3);
            $extracted = [];
            while (!empty($tail)) {
                $candidate = end($tail);
                if (str_word_count($candidate) <= 5 && mb_strlen($candidate) <= 60) {
                    array_unshift($extracted, array_pop($tail));
                } else {
                    break;
                }
            }
            if (count($extracted) >= 2) {
                array_splice($buf, count($buf) - count($extracted));
                $this->applyMastheadCandidateLines($extracted, $personal);
            }
        }
    }

    protected function applyMastheadCandidateLines(array $candidates, array &$personal): void
    {
        $filtered = [];
        foreach ($candidates as $c) {
            $c = trim($c);
            if ($c === '' || preg_match('/^[A-Z]{1,3}$/', $c)) continue;
            // Skip bulleted tagline strips like "Events • Hospitality • Catering" after title
            if (substr_count($c, '•') >= 2 && count($filtered) >= 2) {
                continue;
            }
            $filtered[] = $c;
        }
        if (!empty($filtered[0])) {
            $personal['full_name'] = mb_substr($filtered[0], 0, 80);
        }
        if (!empty($filtered[1])) {
            $personal['job_title'] = mb_substr($filtered[1], 0, 100);
        }
    }

    /**
     * Parse experience roles supporting both calendar date ranges ("2019 – 2022", "Jan 2021 - Present")
     * and duration lines ("Company Name | 2 years – Present", "Company Name | 3 years", "Company | 1 year").
     */
    protected function parseExperienceSection(array $lines): array
    {
        $calendarRangeRegex = '/((?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Sept|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:0?[1-9]|1[0-2])\/\d{4}|\b(?:19|20)\d{2}\b)\s*[-–—to]+\s*(Present|Current|Now|(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Sept|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:0?[1-9]|1[0-2])\/\d{4}|\b(?:19|20)\d{2}\b)/iu';
        $durationLineRegex = '/(?:^|[|–—\-]\s*)(\d+\s*(?:\+\s*)?(?:years?|yrs?|months?|mos?))(?:\s*[-–—to]+\s*(Present|Current|Now|\d+\s*(?:years?|yrs?|months?|mos?)))?\s*$/iu';

        $isHeaderOrDateLine = static function (string $l) use ($calendarRangeRegex, $durationLineRegex): bool {
            return (bool) (preg_match($calendarRangeRegex, $l) || preg_match($durationLineRegex, $l));
        };

        $entries = [];
        $current = null;
        $prevLine = '';

        foreach ($lines as $idx => $line) {
            $isBullet = (bool) preg_match('/^[•●▪▫▸►\-\*]\s+/u', $line);

            $dm = null;
            if (!$isBullet && preg_match($calendarRangeRegex, $line, $m)) {
                $dm = ['full' => $m[0], 'start' => trim($m[1]), 'end' => trim($m[2])];
            } elseif (!$isBullet && preg_match($durationLineRegex, $line, $m)) {
                $dm = ['full' => $m[0], 'start' => trim($m[1]), 'end' => isset($m[2]) ? trim($m[2]) : ''];
            }

            if ($dm !== null) {
                $withoutDate = $this->trimPunct(str_replace($dm['full'], '', $line));
                $role = '';
                $company = '';
                $location = '';

                if ($withoutDate !== '') {
                    $segments = preg_split('/\s*[|@]\s*|\s+at\s+|\s+[–—]\s+/iu', $withoutDate) ?: [];
                    $firstSeg = trim($segments[0] ?? '');
                    $secondSeg = trim($segments[1] ?? '');

                    if ($secondSeg !== '') {
                        $role = $firstSeg;
                        $company = $secondSeg;
                        $location = trim($segments[2] ?? '');
                    } else {
                        // Single segment beside date/duration (e.g. "Lahore Country Club | 2 years – Present")
                        $company = $firstSeg;
                    }
                }

                // Pop dangling short non-bullet heading (role title) from previous job's trailing text or prevLine
                if ($current !== null) {
                    $entries[] = $current;
                    $lastIdx = count($entries) - 1;
                    $lastBulletLines = $entries[$lastIdx]['bullets_text'] !== ''
                        ? explode("\n", trim($entries[$lastIdx]['bullets_text']))
                        : [];
                    $popped = [];
                    while (!empty($lastBulletLines)) {
                        $tail = trim((string) end($lastBulletLines));
                        if ($tail !== '' && !preg_match('/^[•●▪▫▸►\-\*]/u', $tail) && mb_strlen($tail) < 75 && !str_ends_with($tail, '.')) {
                            array_unshift($popped, array_pop($lastBulletLines));
                        } else {
                            break;
                        }
                    }
                    if (!empty($popped)) {
                        $entries[$lastIdx]['bullets_text'] = implode("\n", $lastBulletLines);
                        if ($role === '' && count($popped) === 1 && $company !== '') {
                            $role = $popped[0];
                        } else {
                            if ($role === '') $role = $popped[0] ?? '';
                            if ($company === '') $company = $popped[1] ?? '';
                        }
                    }
                } elseif ($prevLine !== '' && $role === '') {
                    $role = $prevLine;
                }

                if ($role === '' && $idx > 0 && empty($entries) && !$isHeaderOrDateLine($lines[$idx - 1])) {
                    $role = $lines[$idx - 1];
                }

                $current = [
                    'role' => $role ?: ($company ?: 'Position'),
                    'company' => ($company !== $role) ? $company : '',
                    'location' => $location,
                    'start_date' => $dm['start'],
                    'end_date' => $dm['end'],
                    'bullets_text' => '',
                ];
                $prevLine = $line;
                continue;
            }

            if ($current === null) {
                $prevLine = $line;
                continue;
            }

            if (!$isBullet && trim($current['company']) === '' && mb_strlen($line) < 65 && !str_ends_with($line, '.')) {
                $parts = preg_split('/\s*[|,–—]\s*/u', $line) ?: [];
                $current['company'] = trim($parts[0]);
                if (isset($parts[1]) && $current['location'] === '') {
                    $current['location'] = trim($parts[1]);
                }
            } else {
                // Keep raw marker status so next role's pop logic can distinguish role title headings from wrapped bullets
                $nextIsDateLine = isset($lines[$idx + 1]) && $isHeaderOrDateLine($lines[$idx + 1]);
                if ($isBullet) {
                    $cleanBullet = '• ' . trim((string) preg_replace('/^[•●▪▫▸►\-\*]\s*/u', '', $line));
                    $current['bullets_text'] .= ($current['bullets_text'] !== '' ? "\n" : '') . $cleanBullet;
                } elseif ($nextIsDateLine && mb_strlen($line) < 75 && !str_ends_with($line, '.')) {
                    // Preserve without bullet prefix so the next iteration pops this as the upcoming role title
                    $current['bullets_text'] .= ($current['bullets_text'] !== '' ? "\n" : '') . $line;
                } elseif ($current['bullets_text'] !== '') {
                    // Check whether this non-bullet line is a PDF line-wrap continuation of the last bullet
                    $bulletArr = explode("\n", $current['bullets_text']);
                    $lastLine = (string) end($bulletArr);
                    $startsLower = (bool) preg_match('/^[a-z]/u', $line);
                    $lastEndedCleanly = (bool) preg_match('/[.!?]$/u', trim($lastLine));

                    if ($startsLower || !$lastEndedCleanly) {
                        $bulletArr[count($bulletArr) - 1] = rtrim($lastLine) . ' ' . $line;
                        $current['bullets_text'] = implode("\n", $bulletArr);
                    } else {
                        $current['bullets_text'] .= "\n• " . $line;
                    }
                } else {
                    $current['bullets_text'] = '• ' . $line;
                }
            }

            $prevLine = $line;
        }

        if ($current !== null) {
            $entries[] = $current;
        }

        // Normalize bullet formatting across entries
        foreach ($entries as &$e) {
            if ($e['bullets_text'] !== '') {
                $bLines = explode("\n", $e['bullets_text']);
                $bLines = array_map(fn ($b) => str_starts_with(trim($b), '•') ? trim($b) : '• ' . trim($b), $bLines);
                $e['bullets_text'] = implode("\n", $bLines);
            }
        }
        unset($e);

        if (empty($entries) && !empty($lines)) {
            $first = array_shift($lines);
            $entries[] = [
                'role' => $first,
                'company' => '',
                'location' => '',
                'start_date' => '',
                'end_date' => '',
                'bullets_text' => implode("\n", array_map(fn ($l) => str_starts_with($l, '•') ? $l : '• ' . ltrim($l, '-* '), $lines)),
            ];
        }

        return $entries;
    }

    /**
     * Convert compact Earlier Experience rows (e.g. "Supervisor – Crown Marriage Hall (5 months)") into one concise entry.
     */
    protected function parseEarlierExperienceSection(array $lines): array
    {
        $bullets = [];
        foreach ($lines as $line) {
            $clean = trim((string) preg_replace('/^[•●▪▫▸►\-\*]\s*/u', '', $line));
            if ($clean === '' || preg_match('/^references available/i', $clean)) continue;
            $bullets[] = '• ' . $clean;
        }

        if (empty($bullets)) {
            return [];
        }

        return [[
            'role' => 'Earlier Experience',
            'company' => 'Prior Operations & Accounting Roles',
            'location' => '',
            'start_date' => '',
            'end_date' => '',
            'bullets_text' => implode("\n", $bullets),
        ]];
    }

    protected function parseEducationSection(array $lines): array
    {
        $dateRegex = '/((?:19|20)\d{2})\s*(?:[-–—to]+\s*((?:19|20)\d{2}|Present|Expected))?/iu';
        $degreeRegex = '/\b(Bachelor|Master|MBA|M\.?S\.?|M\.?Sc|M\.?Phil|B\.?S\.?|B\.?Sc|B\.?A\.?|M\.?A\.?|B\.?Com|D\.?Com|M\.?Com|I\.?Com|BBA|BCS|BIT|BSN|LLB|LLM|MD|Ph\.?D|Doctorate|Associate|Diploma|Certificate|Matric(?:ulation)?|Intermediate|F\.?Sc|ICS|O-?Levels?|A-?Levels?|SSC|HSC|High\s+School|B\.?Ed|M\.?Ed|B\.?Tech|M\.?Tech)\b/i';

        $entries = [];
        $current = null;

        foreach ($lines as $line) {
            $hasDate = (bool) preg_match($dateRegex, $line, $dm);
            $hasDegree = (bool) preg_match($degreeRegex, $line);

            if ($hasDegree && ($current === null || $current['degree'] !== '')) {
                if ($current !== null) $entries[] = $current;
                $clean = $hasDate ? $this->trimPunct(str_replace($dm[0], '', $line)) : $line;
                $parts = preg_split('/\s*[|,]\s*|\s+[–—]\s+/u', $clean, 2) ?: [];
                $degreePart = trim($parts[0]);
                $secondPart = trim($parts[1] ?? '');
                $schoolPart = '';
                $detailsPart = '';

                // Guard against treating status tokens like "Completed" as the university name
                if ($secondPart !== '') {
                    if (preg_match('/^(completed|ongoing|in progress|pursuing)$/i', $secondPart)) {
                        $detailsPart = ucfirst(strtolower($secondPart));
                    } else {
                        $schoolPart = $secondPart;
                    }
                }

                $current = [
                    'degree' => $degreePart,
                    'school' => $schoolPart,
                    'location' => '',
                    'start_date' => $hasDate ? ($dm[1] ?? '') : '',
                    'end_date' => $hasDate ? ($dm[2] ?? '') : '',
                    'details' => $detailsPart,
                ];
                continue;
            }

            if ($current === null) {
                $clean = $hasDate ? $this->trimPunct(str_replace($dm[0], '', $line)) : $line;
                $current = [
                    'degree' => $clean,
                    'school' => '',
                    'location' => '',
                    'start_date' => $hasDate ? ($dm[1] ?? '') : '',
                    'end_date' => $hasDate ? ($dm[2] ?? '') : '',
                    'details' => '',
                ];
                continue;
            }

            // Unwrap multi-line university / board names when ending in connector or next line starts lowercase/preposition
            if ($current['school'] !== ''
                && !$hasDate
                && !preg_match('/^(cgpa|gpa|grade|division|percentage)\b/i', $line)
                && (preg_match('/[,&\-\/]|\b(of|and|for)$/i', $current['school']) || preg_match('/^(of|the|and|&|for|in|at|[a-z])/u', $line))) {
                $current['school'] .= ' ' . $line;
                continue;
            }

            if ($hasDate && $current['start_date'] === '') {
                $current['start_date'] = $dm[1] ?? '';
                $current['end_date'] = $dm[2] ?? '';
                $rest = $this->trimPunct(str_replace($dm[0], '', $line));
                if ($rest !== '' && $current['school'] === '') {
                    $current['school'] = $rest;
                } elseif ($rest !== '') {
                    $current['details'] .= ($current['details'] !== '' ? ' • ' : '') . $rest;
                }
            } elseif ($current['school'] === '') {
                $current['school'] = $line;
            } else {
                $current['details'] .= ($current['details'] !== '' ? ' • ' : '') . $line;
            }
        }

        if ($current !== null) {
            $entries[] = $current;
        }

        return $entries;
    }

    protected function parseSkillsSection(array $lines): array
    {
        $skills = [];
        foreach ($lines as $line) {
            $line = (string) preg_replace('/^[A-Za-z &\/]+:\s*/u', '', $line);
            $chunks = preg_split('/[,|•●·;]+/u', $line) ?: [];
            foreach ($chunks as $chunk) {
                $s = $this->trimPunct($chunk);
                if ($s !== '' && mb_strlen($s) <= 50) {
                    $skills[] = $s;
                }
            }
        }
        return array_values(array_unique(array_slice($skills, 0, 24)));
    }

    protected function parseLanguagesSection(array $lines): array
    {
        $langs = [];
        foreach ($lines as $line) {
            $chunks = preg_split('/[,;|•·]+/u', $line) ?: [];
            foreach ($chunks as $chunk) {
                $chunk = trim($chunk, " -*\t");
                if ($chunk === '' || mb_strlen($chunk) > 45) continue;

                $score = 4;
                $level = 'Proficient';
                if (preg_match('/native|bilingual|mother/i', $chunk)) {
                    $score = 5;
                    $level = 'Native';
                } elseif (preg_match('/fluent|c1|c2|proficient/i', $chunk)) {
                    $score = 4;
                    $level = 'Fluent';
                } elseif (preg_match('/intermediate|b1|b2|conversational/i', $chunk)) {
                    $score = 3;
                    $level = 'Intermediate';
                } elseif (preg_match('/basic|beginner|elementary|a1|a2/i', $chunk)) {
                    $score = 2;
                    $level = 'Basic';
                }

                $name = $this->trimPunct((string) preg_replace('/[\(\[].*?[\)\]]|[-–:]\s*(native|fluent|bilingual|intermediate|conversational|basic|c1|c2|b1|b2|a1|a2).*/iu', '', $chunk));
                if ($name !== '') {
                    $langs[] = ['name' => $name, 'level' => $level, 'score' => $score];
                }
            }
        }
        return array_slice($langs, 0, 8);
    }

    protected function parseAwardsSection(array $lines): array
    {
        $awards = [];
        foreach ($lines as $line) {
            $clean = trim((string) preg_replace('/^[•●▪\-\*]\s*/u', '', $line));
            if ($clean === '') continue;

            $date = '';
            if (preg_match('/\b(19|20)\d{2}\b/', $clean, $dm)) {
                $date = $dm[0];
                $clean = $this->trimPunct(str_replace($date, '', $clean));
            }
            $parts = preg_split('/\s*[|–—]\s*|\s+-\s+/u', $clean, 2) ?: [];
            $awards[] = [
                'title' => trim($parts[0]),
                'issuer' => trim($parts[1] ?? ''),
                'date' => $date,
            ];
        }
        return array_slice($awards, 0, 8);
    }

    protected function parseProjectsSection(array $lines): array
    {
        $projects = [];
        $current = null;

        foreach ($lines as $line) {
            $isBullet = (bool) preg_match('/^[•●▪\-\*]\s+/u', $line);
            if (!$isBullet && mb_strlen($line) < 65 && !str_ends_with($line, '.')) {
                if ($current !== null) $projects[] = $current;
                $parts = preg_split('/\s*[|–—]\s*/u', $line, 2) ?: [];
                $current = [
                    'name' => trim($parts[0]),
                    'role' => trim($parts[1] ?? ''),
                    'link' => '',
                    'description' => '',
                ];
            } else {
                if ($current === null) {
                    $current = ['name' => 'Key Project', 'role' => '', 'link' => '', 'description' => ''];
                }
                $clean = (string) preg_replace('/^[•●▪\-\*]\s*/u', '', $line);
                $current['description'] .= ($current['description'] !== '' ? ' ' : '') . $clean;
            }
        }

        if ($current !== null) {
            $projects[] = $current;
        }

        return array_slice($projects, 0, 6);
    }

    protected function parseJsonPayload(array $json): array
    {
        $content = ResumeCatalog::blankContent();
        $content['experience'] = [];
        $content['education'] = [];
        $content['skills'] = [];
        $content['languages'] = [];
        $content['awards'] = [];
        $content['projects'] = [];

        if (isset($json['personal']) && is_array($json['personal'])) {
            return array_replace_recursive($content, $json);
        }

        $basics = $json['basics'] ?? [];
        $content['personal']['full_name'] = $basics['name'] ?? '';
        $content['personal']['job_title'] = $basics['label'] ?? '';
        $content['personal']['email'] = $basics['email'] ?? '';
        $content['personal']['phone'] = $basics['phone'] ?? '';
        $content['personal']['website'] = $basics['url'] ?? '';
        $content['personal']['location'] = trim(($basics['location']['city'] ?? '') . ', ' . ($basics['location']['countryCode'] ?? ''), ', ');
        $content['summary'] = $basics['summary'] ?? '';

        if (!empty($json['work']) && is_array($json['work'])) {
            $content['experience'] = array_map(fn ($w) => [
                'role' => $w['position'] ?? '',
                'company' => $w['name'] ?? '',
                'location' => $w['location'] ?? '',
                'start_date' => $w['startDate'] ?? '',
                'end_date' => $w['endDate'] ?? 'Present',
                'bullets_text' => implode("\n", array_map(fn ($h) => '• ' . $h, $w['highlights'] ?? [])),
            ], $json['work']);
        }

        if (!empty($json['education']) && is_array($json['education'])) {
            $content['education'] = array_map(fn ($e) => [
                'degree' => trim(($e['studyType'] ?? '') . ' ' . ($e['area'] ?? '')),
                'school' => $e['institution'] ?? '',
                'location' => '',
                'start_date' => $e['startDate'] ?? '',
                'end_date' => $e['endDate'] ?? '',
                'details' => $e['score'] ?? '',
            ], $json['education']);
        }

        if (!empty($json['skills']) && is_array($json['skills'])) {
            $skills = [];
            foreach ($json['skills'] as $sk) {
                if (is_string($sk)) $skills[] = $sk;
                elseif (!empty($sk['name'])) $skills[] = $sk['name'];
            }
            $content['skills'] = $skills;
        }

        if (!empty($json['languages']) && is_array($json['languages'])) {
            $content['languages'] = array_map(fn ($l) => [
                'name' => $l['language'] ?? ($l['name'] ?? ''),
                'level' => $l['fluency'] ?? 'Proficient',
                'score' => 4,
            ], $json['languages']);
        }

        $content['section_visibility'] = [
            'summary' => trim($content['summary']) !== '',
            'experience' => count($content['experience']) > 0,
            'education' => count($content['education']) > 0,
            'skills' => count($content['skills']) > 0,
            'languages' => count($content['languages']) > 0,
            'awards' => count($content['awards']) > 0,
            'projects' => count($content['projects']) > 0,
        ];

        return $content;
    }
}
