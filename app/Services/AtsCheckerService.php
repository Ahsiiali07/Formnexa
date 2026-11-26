<?php

namespace App\Services;

use App\Support\ResumeCatalog;
use Illuminate\Http\UploadedFile;

class AtsCheckerService
{
    protected ResumeParser $parser;

    /**
     * Common high-impact action verbs valued by ATS and hiring recruiters.
     */
    protected array $actionVerbs = [
        'accelerated', 'achieved', 'acquired', 'administered', 'advised', 'analyzed', 'architected',
        'automated', 'built', 'championed', 'coached', 'collaborated', 'completed', 'composed',
        'conceptualized', 'conducted', 'configured', 'constructed', 'coordinated', 'created',
        'delivered', 'deployed', 'designed', 'developed', 'devised', 'directed', 'distributed',
        'documented', 'doubled', 'drafted', 'drove', 'eliminated', 'engineered', 'enhanced',
        'established', 'evaluated', 'executed', 'expanded', 'expedited', 'facilitated', 'formulated',
        'fostered', 'generated', 'governed', 'guided', 'headed', 'identified', 'implemented',
        'improved', 'increased', 'initiated', 'innovated', 'inspected', 'installed', 'instituted',
        'instructed', 'integrated', 'introduced', 'invented', 'launched', 'led', 'managed',
        'maximized', 'mentored', 'minimized', 'modernized', 'negotiated', 'optimized', 'orchestrated',
        'organized', 'overhauled', 'oversaw', 'partnered', 'performed', 'pioneered', 'planned',
        'prepared', 'presented', 'produced', 'programmed', 'promoted', 'published', 'rebuilt',
        'recruited', 'redesigned', 'reduced', 'reengineered', 'reformed', 'remodeled', 'reorganized',
        'resolved', 'restructured', 'revamped', 'revitalized', 'saved', 'scaled', 'scheduled',
        'secured', 'simplified', 'spearheaded', 'standardized', 'streamlined', 'strengthened',
        'structured', 'supervised', 'synthesized', 'systematized', 'tested', 'tracked', 'trained',
        'transformed', 'unified', 'upgraded', 'validated', 'yielded',
    ];

    /**
     * Stopwords excluded from job description keyword extraction.
     */
    protected array $stopwords = [
        'about', 'above', 'after', 'again', 'against', 'all', 'along', 'also', 'although', 'among',
        'and', 'another', 'any', 'are', 'around', 'because', 'been', 'before', 'being', 'below',
        'between', 'both', 'candidate', 'candidates', 'could', 'company', 'during', 'each', 'either',
        'every', 'for', 'from', 'further', 'have', 'having', 'into', 'just', 'more', 'most', 'must',
        'neither', 'only', 'other', 'our', 'out', 'over', 'per', 'please', 'plus', 'preferred',
        'qualifications', 'required', 'requirements', 'responsibilities', 'role', 'same', 'should',
        'some', 'such', 'than', 'that', 'the', 'their', 'theirs', 'them', 'then', 'there', 'these',
        'they', 'this', 'those', 'through', 'under', 'until', 'upon', 'very', 'were', 'what', 'when',
        'where', 'which', 'while', 'who', 'whom', 'why', 'will', 'with', 'within', 'without', 'would',
        'work', 'working', 'experience', 'years', 'ability', 'skills', 'team', 'ideal', 'looking',
    ];

    public function __construct(ResumeParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Extract text from uploaded file or use raw string, then run comprehensive ATS audit.
     */
    public function audit(?UploadedFile $file, ?string $rawText, ?string $jobDescription = null): array
    {
        $resumeText = '';
        $fileName = 'Pasted CV';

        if ($file) {
            $resumeText = $this->parser->extractTextFromFile($file);
            $fileName = $file->getClientOriginalName();
        } elseif (!empty($rawText)) {
            $resumeText = trim($rawText);
        }

        if (trim($resumeText) === '') {
            throw new \InvalidArgumentException('Please upload a resume file (PDF, DOCX, TXT) or paste your resume text.');
        }

        // Parse structured entities using the robust ResumeParser
        $parsed = $this->parser->parse($resumeText);

        // Run 5-dimension evaluation
        $contactAudit = $this->auditContactInfo($parsed['personal'] ?? [], $resumeText);
        $sectionAudit = $this->auditSections($parsed);
        $impactAudit = $this->auditImpactAndActionVerbs($resumeText);
        $formatAudit = $this->auditFormatting($resumeText);
        $keywordAudit = $this->auditKeywords($resumeText, $jobDescription);

        // Overall score weighting:
        // Contact: 15%, Structure: 25%, Impact/Verbs: 25%, Format: 15%, Keywords: 20%
        if (!empty($jobDescription)) {
            $overallScore = round(
                ($contactAudit['score'] * 0.15) +
                ($sectionAudit['score'] * 0.25) +
                ($impactAudit['score'] * 0.25) +
                ($formatAudit['score'] * 0.15) +
                ($keywordAudit['score'] * 0.20)
            );
        } else {
            // When no job description provided, redistribute the keyword weight across Structure and Impact
            $overallScore = round(
                ($contactAudit['score'] * 0.20) +
                ($sectionAudit['score'] * 0.30) +
                ($impactAudit['score'] * 0.30) +
                ($formatAudit['score'] * 0.20)
            );
        }

        $overallScore = max(10, min(100, (int) $overallScore));

        // Rating Grade & Summary
        [$grade, $badgeColor, $verdict] = $this->calculateGrade($overallScore);

        // Compile actionable recommendations
        $recommendations = $this->compileRecommendations(
            $contactAudit,
            $sectionAudit,
            $impactAudit,
            $formatAudit,
            $keywordAudit
        );

        return [
            'success' => true,
            'file_name' => $fileName,
            'overall_score' => $overallScore,
            'grade' => $grade,
            'badge_color' => $badgeColor,
            'verdict' => $verdict,
            'word_count' => str_word_count($resumeText),
            'reading_time_seconds' => max(15, round(str_word_count($resumeText) / 3.5)), // 200 wpm
            'categories' => [
                'contact' => $contactAudit,
                'sections' => $sectionAudit,
                'impact' => $impactAudit,
                'formatting' => $formatAudit,
                'keywords' => $keywordAudit,
            ],
            'recommendations' => $recommendations,
            'parsed_resume' => $parsed,
        ];
    }

    /**
     * 1. Audit Contact Information (100-point scale).
     */
    protected function auditContactInfo(array $personal, string $rawText): array
    {
        $checks = [];
        $score = 0;

        // Name
        $hasName = !empty($personal['full_name']) && strlen($personal['full_name']) >= 3;
        $checks[] = [
            'title' => 'Full Name',
            'passed' => $hasName,
            'value' => $hasName ? $personal['full_name'] : 'Missing or unclear',
            'tip' => 'Ensure your full name is prominently positioned at the top of the resume.',
        ];
        if ($hasName) $score += 25;

        // Email
        $hasEmail = !empty($personal['email']) && filter_var($personal['email'], FILTER_VALIDATE_EMAIL);
        $checks[] = [
            'title' => 'Professional Email Address',
            'passed' => (bool) $hasEmail,
            'value' => $hasEmail ? $personal['email'] : 'Missing valid email',
            'tip' => 'Use a professional email address (e.g. firstname.lastname@gmail.com).',
        ];
        if ($hasEmail) $score += 25;

        // Phone
        $hasPhone = !empty($personal['phone']) && strlen(preg_replace('/[^0-9]/', '', $personal['phone'])) >= 7;
        $checks[] = [
            'title' => 'Phone Number',
            'passed' => (bool) $hasPhone,
            'value' => $hasPhone ? $personal['phone'] : 'Missing phone number',
            'tip' => 'Include an international or standard phone number with country/area code.',
        ];
        if ($hasPhone) $score += 20;

        // LinkedIn / Online Profile
        $hasLinkedin = !empty($personal['website']) ||
            preg_match('/linkedin\.com\/in\/[a-zA-Z0-9_\-]+/i', $rawText) ||
            preg_match('/github\.com\/[a-zA-Z0-9_\-]+/i', $rawText);
        $checks[] = [
            'title' => 'LinkedIn or Portfolio Link',
            'passed' => (bool) $hasLinkedin,
            'value' => $hasLinkedin ? 'Profile link detected' : 'Not detected',
            'tip' => 'Recruiters frequently review LinkedIn or portfolio links alongside your resume. Adding a direct profile URL is recommended.',
        ];
        if ($hasLinkedin) $score += 15;

        // Location / City
        $hasLocation = !empty($personal['location']) || !empty($personal['city_state']);
        $checks[] = [
            'title' => 'Location (City, Country)',
            'passed' => (bool) $hasLocation,
            'value' => $hasLocation ? ($personal['location'] ?? $personal['city_state']) : 'Not detected',
            'tip' => 'Listing your City, State/Country helps location-based ATS filtering.',
        ];
        if ($hasLocation) $score += 15;

        return [
            'name' => 'Contact Information',
            'score' => $score,
            'status' => $score >= 85 ? 'good' : ($score >= 60 ? 'warning' : 'poor'),
            'checks' => $checks,
        ];
    }

    /**
     * 2. Audit Essential Resume Sections (100-point scale).
     */
    protected function auditSections(array $parsed): array
    {
        $checks = [];
        $score = 0;

        // Work Experience
        $expCount = count($parsed['experience'] ?? []);
        $hasExp = $expCount > 0;
        $checks[] = [
            'title' => 'Work Experience Section',
            'passed' => $hasExp,
            'value' => $hasExp ? "{$expCount} position(s) detected" : 'No clear work experience found',
            'tip' => 'Use standard headings like "Work Experience" or "Professional Experience".',
        ];
        if ($expCount >= 2) $score += 35;
        elseif ($expCount === 1) $score += 25;

        // Education
        $eduCount = count($parsed['education'] ?? []);
        $hasEdu = $eduCount > 0;
        $checks[] = [
            'title' => 'Education Section',
            'passed' => $hasEdu,
            'value' => $hasEdu ? "{$eduCount} education item(s)" : 'No clear education section found',
            'tip' => 'List your university degree, college, or major certifications with graduation year.',
        ];
        if ($hasEdu) $score += 25;

        // Skills Section
        $skillsCount = count($parsed['skills'] ?? []);
        $hasSkills = $skillsCount >= 3;
        $checks[] = [
            'title' => 'Core Skills & Competencies',
            'passed' => $hasSkills,
            'value' => $skillsCount > 0 ? "{$skillsCount} skills recognized" : 'No skills section recognized',
            'tip' => 'Group hard technical skills and tools into a dedicated "Skills" section.',
        ];
        if ($skillsCount >= 6) $score += 25;
        elseif ($skillsCount >= 3) $score += 15;

        // Professional Summary / Profile
        $hasSummary = !empty($parsed['personal']['summary']) && strlen($parsed['personal']['summary']) > 40;
        $checks[] = [
            'title' => 'Professional Summary / Objective',
            'passed' => $hasSummary,
            'value' => $hasSummary ? 'Summary statement present' : 'Missing or too short',
            'tip' => 'A 2-3 sentence executive summary gives hiring managers an instant snapshot of your value.',
        ];
        if ($hasSummary) $score += 15;

        return [
            'name' => 'Section Structure',
            'score' => min(100, $score),
            'status' => $score >= 80 ? 'good' : ($score >= 50 ? 'warning' : 'poor'),
            'checks' => $checks,
        ];
    }

    /**
     * 3. Audit Impact, Metrics & Action Verbs (100-point scale).
     */
    protected function auditImpactAndActionVerbs(string $rawText): array
    {
        $checks = [];
        $lower = strtolower($rawText);

        // Find matched action verbs
        $matchedVerbs = [];
        foreach ($this->actionVerbs as $verb) {
            if (preg_match('/\b' . preg_quote($verb, '/') . '\b/i', $lower)) {
                $matchedVerbs[] = $verb;
            }
        }
        $matchedVerbs = array_unique($matchedVerbs);
        $verbCount = count($matchedVerbs);

        // Find quantifiable metrics (percentages, dollar amounts, numbers)
        preg_match_all('/\b\d+(\.\d+)?%\b|\$\d+([kmb])?|\b\d{1,4}\s*(percent|users|clients|projects|million|k|members|engineers|people)\b/i', $rawText, $metricMatches);
        $metricsFound = array_unique($metricMatches[0] ?? []);
        $metricCount = count($metricsFound);

        $verbScore = min(50, round(($verbCount / 10) * 50));
        $metricScore = min(50, round(($metricCount / 5) * 50));
        $totalScore = $verbScore + $metricScore;

        $checks[] = [
            'title' => 'Power Action Verbs',
            'passed' => $verbCount >= 6,
            'value' => "{$verbCount} unique action verbs detected (" . implode(', ', array_slice($matchedVerbs, 0, 8)) . (count($matchedVerbs) > 8 ? '...' : '') . ')',
            'tip' => 'Begin each work experience bullet with a strong action verb (e.g. Spearheaded, Engineered, Optimized).',
        ];

        $checks[] = [
            'title' => 'Quantifiable Metrics & Achievements',
            'passed' => $metricCount >= 3,
            'value' => $metricCount > 0 ? "{$metricCount} metrics detected (" . implode(', ', array_slice($metricsFound, 0, 5)) . ')' : 'No clear metrics or percentages found',
            'tip' => 'Back up your accomplishments with measurable numbers (e.g., "Increased sales by 32%", "Cut latency by 180ms").',
        ];

        return [
            'name' => 'Impact & Action Verbs',
            'score' => $totalScore,
            'status' => $totalScore >= 75 ? 'good' : ($totalScore >= 45 ? 'warning' : 'poor'),
            'checks' => $checks,
            'action_verbs_found' => array_slice($matchedVerbs, 0, 15),
            'metrics_found' => array_slice($metricsFound, 0, 8),
        ];
    }

    /**
     * 4. Audit Formatting, Length & ATS Readability (100-point scale).
     */
    protected function auditFormatting(string $rawText): array
    {
        $checks = [];
        $score = 100;
        $wordCount = str_word_count($rawText);

        // Word Count Check (Ideal: 350 to 950 words)
        $wordStatus = true;
        $wordMessage = "Optimal length ({$wordCount} words)";
        if ($wordCount < 200) {
            $score -= 30;
            $wordStatus = false;
            $wordMessage = "Too short ({$wordCount} words). A comprehensive 1-page CV typically has 350-700 words.";
        } elseif ($wordCount > 1300) {
            $score -= 20;
            $wordStatus = false;
            $wordMessage = "Slightly too long ({$wordCount} words). Try to condense to 1-2 focused pages.";
        }
        $checks[] = [
            'title' => 'Resume Length & Word Count',
            'passed' => $wordStatus,
            'value' => $wordMessage,
            'tip' => 'Aim for 400 to 800 words for early to mid-level professionals.',
        ];

        // Bullet Point Formatting
        preg_match_all('/^[\s\t]*[\x{2022}\x{25CF}\x{2013}\x{2014}\-\*\>]+\s+/mu', $rawText, $bulletMatches);
        $bulletCount = count($bulletMatches[0] ?? []);
        $hasBullets = $bulletCount >= 4 || str_contains($rawText, "\n- ") || str_contains($rawText, "\n* ");
        $checks[] = [
            'title' => 'Bullet Point Structure',
            'passed' => $hasBullets,
            'value' => $hasBullets ? 'Well-structured bullet points detected' : 'Few or no bullet points detected',
            'tip' => 'Break dense paragraphs into punchy 1-2 line bullet points for instant ATS and recruiter scanning.',
        ];
        if (!$hasBullets) $score -= 25;

        // Special Character / Complex Symbol check
        preg_match_all('/[\x{0000}-\x{0008}\x{000E}-\x{001F}\x{FFF0}-\x{FFFF}]/u', $rawText, $badChars);
        $cleanChars = empty($badChars[0]);
        $checks[] = [
            'title' => 'ATS-Safe Characters & Clean Encoding',
            'passed' => $cleanChars,
            'value' => $cleanChars ? 'Clean text encoding (No corrupt characters)' : 'Unusual symbols detected',
            'tip' => 'Avoid complex symbols or nested icons that confuse legacy parser software.',
        ];
        if (!$cleanChars) $score -= 20;

        return [
            'name' => 'ATS Formatting & Readability',
            'score' => max(20, $score),
            'status' => $score >= 80 ? 'good' : ($score >= 50 ? 'warning' : 'poor'),
            'checks' => $checks,
        ];
    }

    /**
     * 5. Audit Job Description Keyword Match (100-point scale).
     */
    protected function auditKeywords(string $rawText, ?string $jobDescription): array
    {
        if (empty($jobDescription) || strlen(trim($jobDescription)) < 30) {
            return [
                'name' => 'Target Keyword Match',
                'score' => 100,
                'status' => 'info',
                'matched' => [],
                'missing' => [],
                'match_percentage' => null,
                'checks' => [
                    [
                        'title' => 'Job Description Matching',
                        'passed' => true,
                        'value' => 'No job description provided (General ATS Audit mode)',
                        'tip' => 'Paste a target job posting above to see exactly which keywords you are missing!',
                    ],
                ],
            ];
        }

        // Extract candidate keywords from Job Description
        $jdKeywords = $this->extractKeyTerms($jobDescription);
        $resumeTextLower = strtolower($rawText);

        $matched = [];
        $missing = [];

        foreach ($jdKeywords as $term) {
            if (preg_match('/\b' . preg_quote($term, '/') . '\b/i', $resumeTextLower)) {
                $matched[] = $term;
            } else {
                $missing[] = $term;
            }
        }

        $totalJdTerms = count($jdKeywords);
        $matchPercentage = $totalJdTerms > 0 ? round((count($matched) / $totalJdTerms) * 100) : 100;
        $score = max(20, min(100, $matchPercentage));

        $checks = [
            [
                'title' => 'Job Keyword Match Rate',
                'passed' => $matchPercentage >= 65,
                'value' => "{$matchPercentage}% match (" . count($matched) . " of {$totalJdTerms} key terms found)",
                'tip' => 'Incorporate the missing keywords naturally into your Skills or Experience bullet points.',
            ],
        ];

        return [
            'name' => 'Target Keyword Match',
            'score' => $score,
            'status' => $score >= 70 ? 'good' : ($score >= 45 ? 'warning' : 'poor'),
            'matched' => array_slice($matched, 0, 16),
            'missing' => array_slice($missing, 0, 16),
            'match_percentage' => $matchPercentage,
            'checks' => $checks,
        ];
    }

    /**
     * Extract meaningful keywords from job descriptions.
     */
    protected function extractKeyTerms(string $text): array
    {
        $clean = preg_replace('/[^a-zA-Z0-9\+\#\.\s\-]/', ' ', strtolower($text));
        $words = preg_split('/\s+/', (string) $clean, -1, PREG_SPLIT_NO_EMPTY);

        $frequencies = [];
        foreach ($words as $word) {
            $word = trim($word, ".-_");
            if (strlen($word) < 3 || in_array($word, $this->stopwords, true)) {
                continue;
            }
            if (is_numeric($word)) {
                continue;
            }
            $frequencies[$word] = ($frequencies[$word] ?? 0) + 1;
        }

        arsort($frequencies);
        return array_slice(array_keys($frequencies), 0, 24);
    }

    /**
     * Compute letter grade and summary verdict.
     */
    protected function calculateGrade(int $score): array
    {
        if ($score >= 88) {
            return ['A+', 'emerald', 'Outstanding! Your resume is highly ATS-compliant and ready for submission.'];
        }
        if ($score >= 80) {
            return ['A', 'emerald', 'Great job! Passes standard ATS parsers with minor areas for polish.'];
        }
        if ($score >= 70) {
            return ['B', 'teal', 'Good foundation, but has several ATS weaknesses that could hurt your ranking.'];
        }
        if ($score >= 55) {
            return ['C', 'amber', 'Needs improvement. Missing essential sections or quantifiable metrics.'];
        }
        return ['D', 'rose', 'Critical ATS issues detected. Risk of automatic rejection by recruiter filters.'];
    }

    /**
     * Compile actionable bullet point recommendations.
     */
    protected function compileRecommendations(array $contact, array $sections, array $impact, array $format, array $keywords): array
    {
        $recs = [];

        foreach ([$contact, $sections, $impact, $format, $keywords] as $cat) {
            foreach ($cat['checks'] as $check) {
                if (!$check['passed']) {
                    $recs[] = [
                        'category' => $cat['name'],
                        'title' => $check['title'],
                        'tip' => $check['tip'],
                        'priority' => in_array($cat['name'], ['Section Structure', 'Contact Information'], true) ? 'high' : 'medium',
                    ];
                }
            }
        }

        if (!empty($keywords['missing'])) {
            $recs[] = [
                'category' => 'Job Keywords',
                'title' => 'Missing Essential Skills',
                'tip' => 'Add relevant keywords from your target job: ' . implode(', ', array_slice($keywords['missing'], 0, 6)),
                'priority' => 'high',
            ];
        }

        // Default praise if everything passed
        if (empty($recs)) {
            $recs[] = [
                'category' => 'General',
                'title' => 'ATS Optimized',
                'tip' => 'Your resume meets modern ATS standards. Make sure to tailor keywords for each job you target!',
                'priority' => 'low',
            ];
        }

        return $recs;
    }
}
