<?php

namespace App\Support;

class ToolSeoCatalog
{
    /**
     * Unique SEO content, checklists, comparisons, FAQs, and internal links for all 12 document generators.
     *
     * @return array<string, mixed>
     */
    public static function document(string $slug): array
    {
        $items = [
            'invoice-generator' => [
                'steps' => [
                    'Add your company logo, cover photo, business name, and optional tax registration (such as NTN/STRN or VAT ID).',
                    'Enter client billing contact details and select any supported currency including USD, EUR, GBP, or PKR.',
                    'Add itemized services or goods with quantities, unit prices, discounts, and tax percentage.',
                    'Sign digitally, include an online payment link, and download a watermark-free PDF or copy a share link.',
                ],
                'checklist' => [
                    'Unique sequential invoice number & issue/due date',
                    'Seller details, tax ID / NTN (optional) & IBAN or bank notes',
                    'Client billing name, company & email address',
                    'Line-by-line descriptions, quantity, rate & tax/discount lines',
                    'Total balance due, payment link & optional authorized signature',
                ],
                'comparison_title' => 'Invoice vs. Proforma Invoice & Receipt',
                'comparison_body' => 'An official Invoice requests payment for delivered work or shipped goods and creates an accounts receivable entry. Send a Proforma Invoice earlier if the buyer needs a preliminary bill before shipping, and issue a Payment Receipt only after funds arrive.',
                'tips' => 'Specify an exact calendar due date (such as Net 14) and attach a Stripe, PayPal, or local bank transfer note so finance teams can settle on first review.',
                'faqs' => [
                    ['q' => 'Can I generate invoices in PKR or USD with tax lines?', 'a' => 'Yes. Switch between 24 currencies including PKR (Rs), USD, EUR, and GBP, and add custom tax percentages, shipping, or discounts inline.'],
                    ['q' => 'Are downloaded invoice PDFs watermark-free?', 'a' => 'Every invoice PDF and shareable link generated on Formnexa is clean, print-ready, and free of any watermark.'],
                    ['q' => 'Can I include my business NTN or VAT number?', 'a' => 'Yes. Enter your NTN, STRN, VAT, or EIN directly in the sender address block or notes area so it renders cleanly on the exported PDF.'],
                    ['q' => 'Do I need an account to create and save invoices?', 'a' => 'No sign-up is required. Your work stays cached in your browser session, and optional accounts let you sync history across devices.'],
                ],
                'related' => [
                    ['Payment Receipt Generator', '/receipt-generator'],
                    ['Quote Generator', '/quote-generator'],
                    ['Proforma Invoice Generator', '/proforma-invoice-generator'],
                    ['Credit Note Generator', '/credit-note-generator'],
                    ['Account Statement Generator', '/account-statement-generator'],
                    ['Invoice Templates Gallery', '/templates'],
                ],
            ],
            'receipt-generator' => [
                'steps' => [
                    'Set your business or merchant details and add your brand logo.',
                    'Enter the customer name and confirmation receipt number (`REC-`).',
                    'List purchased items or settled invoice references with amounts paid.',
                    'Export your Paid-stamped PDF receipt immediately.',
                ],
                'checklist' => [
                    'Merchant name, address & contact details',
                    'Receipt number, payment date & payment method note',
                    'Itemized summary of goods, services, or settled invoice ID',
                    'Tax collected, total amount paid & Paid in Full confirmation',
                ],
                'comparison_title' => 'Payment Receipt vs. Invoice',
                'comparison_body' => 'An Invoice is a request for money still owed; a Receipt is proof that payment has already been received. Issuing a prompt receipt prevents duplicate billing follow-ups and supports buyer expense reimbursement.',
                'tips' => 'Reference the original invoice number in the receipt notes line so both your bookkeeper and client can reconcile transactions cleanly.',
                'faqs' => [
                    ['q' => 'Does the receipt mark the balance as Paid?', 'a' => 'Yes. The Receipt Generator defaults to paid status and clearly reflects payment received in full.'],
                    ['q' => 'Can freelancers use this for cash, card, wire, or bank transfers?', 'a' => 'Yes. Note the transfer reference or payment channel in the notes field before downloading the PDF.'],
                    ['q' => 'Is there a limit on how many receipts I can generate?', 'a' => 'No. Generate and download as many clean receipt PDFs as you need at no charge.'],
                    ['q' => 'Will my receipt include watermarks?', 'a' => 'No. Every PDF exports cleanly with your own branding only.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Account Statement Generator', '/account-statement-generator'],
                    ['Credit Note Generator', '/credit-note-generator'],
                    ['Invoice Templates', '/templates'],
                ],
            ],
            'quote-generator' => [
                'steps' => [
                    'Enter your company contact details and recipient client info.',
                    'Choose an expiration / validity date so pricing stays time-bound.',
                    'Break deliverables down cleanly by phase, SKU, or fixed milestone fee.',
                    'Download the PDF quote or send a live preview link for client approval.',
                ],
                'checklist' => [
                    'Unique Quote ID (`QUO-`) & clear quote expiration date',
                    'Scope of deliverables, quantities & fixed unit rates',
                    'Discounts, projected tax & expected payment terms upon acceptance',
                    'Signature / approval notes for client sign-off',
                ],
                'comparison_title' => 'Quote vs. Estimate & Sales Order',
                'comparison_body' => 'A Quote offers a fixed price valid for a specific window (such as 30 days), whereas an Estimate provides an approximate cost projection that may adjust with actual materials or hours. Once the client approves a quote, convert the scope into a Sales Order or Invoice.',
                'tips' => 'Include clear exclusions and revision limits in the quote notes field so scope changes later are handled transparently.',
                'faqs' => [
                    ['q' => 'How long should a price quote remain valid?', 'a' => 'Most service agencies and suppliers set a 14-day or 30-day validity period to protect against supplier rate changes.'],
                    ['q' => 'Can I reuse the same quote structure for recurring bids?', 'a' => 'Yes. Draft details stay saved locally so you can duplicate and adjust line items in seconds.'],
                    ['q' => 'Can clients accept a quote online?', 'a' => 'Share the generated link cleanly or export a clean PDF for email sign-off.'],
                    ['q' => 'Does Formnexa add watermarks to quotes?', 'a' => 'Never. Every quote PDF downloads without watermarks.'],
                ],
                'related' => [
                    ['Estimate Generator', '/estimate-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Sales Order Generator', '/sales-order-generator'],
                    ['Proforma Invoice Generator', '/proforma-invoice-generator'],
                ],
            ],
            'estimate-generator' => [
                'steps' => [
                    'Fill in contractor or studio details and the prospective client address.',
                    'Separate labor, parts, and consulting line items clearly.',
                    'Include estimated tax or contingency percentages where relevant.',
                    'Export an itemized job estimate PDF before starting work.',
                ],
                'checklist' => [
                    'Estimate number (`EST-`), date & projected expiry window',
                    'Expected labor hours or milestone estimates & material rates',
                    'Assumptions, exclusions & deposit requirements in notes',
                    'Subtotal, estimated tax & projected grand total',
                ],
                'comparison_title' => 'Job Estimate vs. Fixed Quote',
                'comparison_body' => 'Choose an Estimate when exact hours, site materials, or third-party costs cannot be locked 100% upfront — such as construction repairs, custom software sprints, or renovations. Choose a Quote when you guarantee a fixed price.',
                'tips' => 'Label variable items clearly as estimated hours so clients expect minor variances before final invoicing.',
                'faqs' => [
                    ['q' => 'Is an estimate legally binding like an invoice?', 'a' => 'An estimate is a good-faith cost projection prior to engagement, not a final payment demand.'],
                    ['q' => 'Can I include both hourly labor and flat material items?', 'a' => 'Yes. Add as many line rows as needed with custom quantities and unit rates.'],
                    ['q' => 'Can I switch currency to PKR, GBP, USD, or EUR?', 'a' => 'Yes, select from 24 supported currencies at the top of the studio.'],
                    ['q' => 'Is a login required to download my estimate?', 'a' => 'No login or credit card is needed.'],
                ],
                'related' => [
                    ['Quote Generator', '/quote-generator'],
                    ['Work Order Generator', '/work-order-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Timesheet Generator', '/timesheet-generator'],
                ],
            ],
            'credit-note-generator' => [
                'steps' => [
                    'Enter your supplier details and the customer account receiving the credit.',
                    'Reference the original invoice number and reason for refund or adjustment.',
                    'Itemize returned goods or credited billing adjustments.',
                    'Download a clean Credit Note PDF for clean audit records.',
                ],
                'checklist' => [
                    'Unique Credit Note number (`CRN-`) & issue date',
                    'Reference to the original Invoice ID being credited',
                    'Reason code or explanation (return, overbilling, discount)',
                    'Credited tax adjustment & net credit amount',
                ],
                'comparison_title' => 'Credit Note vs. Standard Invoice',
                'comparison_body' => 'Accountants never delete an issued invoice once posted to ledgers. Instead, you issue a Credit Note (`CRN-`) to offset all or part of that invoice cleanly in both your books and the buyer\'s records.',
                'tips' => 'Follow up a partial credit note with an updated Account Statement so the client sees their exact remaining net balance.',
                'faqs' => [
                    ['q' => 'When should I issue a credit note instead of editing an invoice?', 'a' => 'Whenever an invoice has already been sent, recorded in accounting software, or partially paid.'],
                    ['q' => 'Does a credit note adjust tax amounts too?', 'a' => 'Yes, configure matching tax rates on the credited lines so net adjustments align accurately.'],
                    ['q' => 'Can I download and email the credit note directly?', 'a' => 'Yes, export a crisp PDF or email/share the live document link directly.'],
                    ['q' => 'Is this credit memo tool free?', 'a' => 'Yes, unlimited credit notes with zero watermarks.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Account Statement Generator', '/account-statement-generator'],
                    ['Receipt Generator', '/receipt-generator'],
                ],
            ],
            'purchase-order-generator' => [
                'steps' => [
                    'Enter your purchasing company details as the buyer and select the vendor.',
                    'Set delivery deadlines, shipping destination, and unique PO number (`PO-`).',
                    'List SKUs, quantities, and agreed unit purchase prices.',
                    'Export and send your formal PO PDF to authorize supplier fulfillment.',
                ],
                'checklist' => [
                    'Buyer company info, Ship-To warehouse address & PO number',
                    'Vendor/supplier contact details & payment terms (e.g. Net 30)',
                    'Item SKU descriptions, exact quantities & agreed unit cost',
                    'Authorizing buyer signature & delivery instructions',
                ],
                'comparison_title' => 'Purchase Order (PO) vs. Sales Order & Invoice',
                'comparison_body' => 'The buyer creates a Purchase Order to request and authorize goods; the vendor replies with a Sales Order confirming stock and dispatch, and later bills against that PO number using a commercial Invoice.',
                'tips' => 'Require suppliers to quote your `PO-` number on packing slips and invoices for effortless three-way matching.',
                'faqs' => [
                    ['q' => 'Who issues a Purchase Order — the buyer or seller?', 'a' => 'The buyer issues a Purchase Order to formally authorize a vendor to ship items or deliver services at agreed pricing.'],
                    ['q' => 'Can I add digital signatures to approve a PO?', 'a' => 'Yes, use the built-in digital signature pad before exporting to PDF.'],
                    ['q' => 'Are multi-currency POs supported?', 'a' => 'Yes, choose USD, PKR, EUR, GBP, AED, and 19 more currencies.'],
                    ['q' => 'Do I need to register to print POs?', 'a' => 'No account required.'],
                ],
                'related' => [
                    ['Sales Order Generator', '/sales-order-generator'],
                    ['Packing Slip Generator', '/packing-slip-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Proforma Invoice Generator', '/proforma-invoice-generator'],
                ],
            ],
            'sales-order-generator' => [
                'steps' => [
                    'Add your seller company details and customer shipping/billing info.',
                    'Enter the matching Sales Order number (`SO-`) and customer PO reference.',
                    'Confirm ordered quantities, unit pricing, taxes, and dispatch terms.',
                    'Download or share the confirmed Sales Order PDF.',
                ],
                'checklist' => [
                    'Seller company details & unique Sales Order ID (`SO-`)',
                    'Customer billing/shipping addresses & buyer PO reference',
                    'Confirmed SKU quantities, unit pricing & tax calculations',
                    'Estimated dispatch timeline & fulfillment terms',
                ],
                'comparison_title' => 'Sales Order vs. Purchase Order',
                'comparison_body' => 'A Purchase Order comes from the customer requesting stock, whereas a Sales Order is generated internally by the seller to confirm stock allocation, pricing, and warehouse dispatch dates.',
                'tips' => 'Pass your Sales Order SKU rows cleanly into a Packing Slip when parcels leave the warehouse.',
                'faqs' => [
                    ['q' => 'Why send a Sales Order before invoicing?', 'a' => 'It locks in agreed delivery schedules, pricing, and quantities internally and gives buyers written order confirmation.'],
                    ['q' => 'Can I attach custom shipping fees and discounts?', 'a' => 'Yes, configure shipping, discounts, and taxes right inside the studio summary.'],
                    ['q' => 'Is every Sales Order PDF watermark-free?', 'a' => 'Yes, clean output with no Formnexa branding.'],
                    ['q' => 'Can I save sales orders to history?', 'a' => 'Guest session saving works automatically, or sign in to sync across devices.'],
                ],
                'related' => [
                    ['Purchase Order Generator', '/purchase-order-generator'],
                    ['Packing Slip Generator', '/packing-slip-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Quote Generator', '/quote-generator'],
                ],
            ],
            'proforma-invoice-generator' => [
                'steps' => [
                    'Enter exporter/seller details and international buyer consignee info.',
                    'Set Proforma Invoice ID (`PI-`), currency, and pre-payment terms.',
                    'List product descriptions, HS/SKU notes, quantities, and freight costs.',
                    'Export a customs-ready Proforma Invoice PDF.',
                ],
                'checklist' => [
                    'Clear "PROFORMA INVOICE" title & unique reference (`PI-`)',
                    'Exporter & importer addresses, currency & shipping notes',
                    'Itemized descriptions, quantities, unit value & freight charges',
                    'Validity window & bank wire / advance deposit terms',
                ],
                'comparison_title' => 'Proforma Invoice vs. Commercial Invoice',
                'comparison_body' => 'A Proforma Invoice is a preliminary bill issued before shipment or advance deposit so buyers can arrange import permits, letters of credit, or bank transfers. Once goods ship, issue a final Commercial Invoice.',
                'tips' => 'Include Incoterms (such as FOB or CIF) and expected dispatch lead time inside the Notes box.',
                'faqs' => [
                    ['q' => 'Is a Proforma Invoice recorded as final revenue?', 'a' => 'No. It serves as a formal commitment quote prior to shipment or payment; final bookkeeping uses your commercial Invoice.'],
                    ['q' => 'Can I add freight and insurance lines?', 'a' => 'Yes, use line items or the built-in shipping field for landed cost breakdowns.'],
                    ['q' => 'Which currencies can I select?', 'a' => 'Choose from 24 major trade currencies including USD, EUR, GBP, AED, SAR, CNY, and PKR.'],
                    ['q' => 'Does it require registration?', 'a' => 'No account required to create and download PDFs.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Quote Generator', '/quote-generator'],
                    ['Purchase Order Generator', '/purchase-order-generator'],
                    ['Packing Slip Generator', '/packing-slip-generator'],
                ],
            ],
            'timesheet-generator' => [
                'steps' => [
                    'Add your contractor/agency info and client project manager details.',
                    'Use each row for a day, sprint, or task, setting Qty as billable hours and Rate as hourly rate.',
                    'Review automatically totaled hours and billable amounts.',
                    'Export a clean weekly Timesheet PDF for sign-off.',
                ],
                'checklist' => [
                    'Consultant/worker name, client & billing period dates',
                    'Task/deliverable breakdown with hours worked & hourly rate',
                    'Optional overtime or flat milestone adjustments',
                    'Total billable amount & sign-off signature line',
                ],
                'comparison_title' => 'Timesheet vs. Work Order & Invoice',
                'comparison_body' => 'A Timesheet tracks time logged across days or sprints, a Work Order authorizes specific maintenance tasks and materials, and an Invoice formally bills the approved total.',
                'tips' => 'Include project ticket IDs or sprint dates in item descriptions so finance approvers sign off without follow-up emails.',
                'faqs' => [
                    ['q' => 'Does the timesheet automatically calculate hours multiplied by rate?', 'a' => 'Yes. Enter hours in the quantity column and your hourly fee in the rate column for instant totals.'],
                    ['q' => 'Can clients or supervisors sign the timesheet?', 'a' => 'Yes, capture signatures digitally or leave space for physical sign-off.'],
                    ['q' => 'Can I export weekly or monthly periods?', 'a' => 'Customize date fields and document titles to match weekly, bi-weekly, or monthly cycles.'],
                    ['q' => 'Is it free for contractors?', 'a' => '100% free with zero watermarks.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Work Order Generator', '/work-order-generator'],
                    ['Estimate Generator', '/estimate-generator'],
                ],
            ],
            'work-order-generator' => [
                'steps' => [
                    'Enter service company details and customer site location.',
                    'Summarize requested repair/maintenance scope inside task line items.',
                    'Add labor hours, replacement parts, and estimated or fixed charges.',
                    'Capture client authorization signature and download the PDF.',
                ],
                'checklist' => [
                    'Work Order ID (`WO-`), scheduled date & customer site details',
                    'Clear breakdown of labor tasks, materials & parts used',
                    'Technician notes, safety instructions & completion status',
                    'Client sign-off signature confirming authorized work',
                ],
                'comparison_title' => 'Work Order vs. Estimate & Packing Slip',
                'comparison_body' => 'An Estimate quotes expected project costs before approval; a Work Order instructs technicians what work to perform and captures sign-off; a Packing Slip tracks goods in transit.',
                'tips' => 'Capture the customer signature right on a tablet or phone after completing field work, then generate the final PDF on the spot.',
                'faqs' => [
                    ['q' => 'Can I use this on mobile in the field?', 'a' => 'Yes, the entire studio and signature pad work smoothly on phone and tablet browsers.'],
                    ['q' => 'Can I separate parts and labor costs?', 'a' => 'Yes, add distinct rows for labor rates and material quantities.'],
                    ['q' => 'Are saved drafts lost on refresh?', 'a' => 'No, active drafts auto-cache in your browser storage.'],
                    ['q' => 'Is there a fee per work order?', 'a' => 'No, create unlimited work orders free.'],
                ],
                'related' => [
                    ['Estimate Generator', '/estimate-generator'],
                    ['Timesheet Generator', '/timesheet-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                ],
            ],
            'account-statement-generator' => [
                'steps' => [
                    'Fill in your billing entity details and customer account information.',
                    'List recent invoices, applied payments, or credit adjustments across the period.',
                    'Verify the outstanding net balance due using the built-in amount-paid line.',
                    'Download or email the Account Statement PDF to your client.',
                ],
                'checklist' => [
                    'Statement period dates & Statement ID (`STMT-`)',
                    'Summary of billed invoices, receipts & issued credit notes',
                    'Previous payments applied (`Amount Paid`) & remaining `Balance Due`',
                    'Payment instructions or Pay Now link for remaining dues',
                ],
                'comparison_title' => 'Account Statement vs. Single Invoice',
                'comparison_body' => 'An Invoice bills for one specific order or milestone. An Account Statement summarizes multiple invoices, payments, and credits over a month or quarter to show total balance owed.',
                'tips' => 'Send account statements on the 1st of every month to retainer and wholesale accounts to catch overdue bills early.',
                'faqs' => [
                    ['q' => 'Can I show partial payments already received?', 'a' => 'Yes. Enter received payments in the Amount Paid field so the statement highlights only the net balance due.'],
                    ['q' => 'Can I include overdue invoice numbers on individual lines?', 'a' => 'Yes, list each invoice date and number directly in line-item descriptions.'],
                    ['q' => 'Does it support all major currencies?', 'a' => 'Yes, 24 currencies are supported out of the box.'],
                    ['q' => 'Do clients need a Formnexa account to view a statement link?', 'a' => 'No, share links and PDFs open cleanly for any recipient.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Receipt Generator', '/receipt-generator'],
                    ['Credit Note Generator', '/credit-note-generator'],
                ],
            ],
            'packing-slip-generator' => [
                'steps' => [
                    'Enter sender warehouse address and recipient delivery destination.',
                    'Include the order or Sales Order reference (`PS-`) and package notes.',
                    'List every SKU and shipped quantity (`Qty`) cleanly inside the manifest rows.',
                    'Download or print your crisp Packing Slip PDF to place inside the parcel.',
                ],
                'checklist' => [
                    'Ship-From warehouse & Ship-To customer contact address',
                    'Order reference number, dispatch date & package count note',
                    'Exact SKU description & shipped quantity per item',
                    'Carrier/inspection notes without revealing wholesale prices if desired',
                ],
                'comparison_title' => 'Packing Slip vs. Invoice & Sales Order',
                'comparison_body' => 'While an Invoice and Sales Order focus on payment totals and taxes, a Packing Slip focuses on physical fulfillment accuracy so warehouse staff and receiving customers can verify every item in the box.',
                'tips' => 'Leave unit prices at `0` if sending a gift order or blind drop-shipment where the recipient should not see wholesale pricing.',
                'faqs' => [
                    ['q' => 'Should a packing slip show prices?', 'a' => 'It is optional. For e-commerce gifts or drop-shipping, leave prices at zero and focus on SKU descriptions and quantities.'],
                    ['q' => 'Can I print multiple packing slips quickly?', 'a' => 'Yes, click Print or Download PDF directly from the studio.'],
                    ['q' => 'Can I customize the column labels?', 'a' => 'Yes, rename labels in the studio to match SKU, Box, or Weight columns.'],
                    ['q' => 'Is this packing list maker free?', 'a' => 'Yes, completely free with zero watermarks.'],
                ],
                'related' => [
                    ['Sales Order Generator', '/sales-order-generator'],
                    ['Purchase Order Generator', '/purchase-order-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                ],
            ],
        ];

        return $items[$slug] ?? $items['invoice-generator'];
    }

    /**
     * Unique SEO content, formatting notes, privacy details & FAQs for all 10 PDF converters.
     *
     * @return array<string, mixed>
     */
    public static function converter(string $slug): array
    {
        $items = [
            'jpg-to-pdf' => [
                'steps' => [
                    'Drag or select one or multiple JPG, PNG, or WebP images.',
                    'Choose portrait/landscape orientation, margin size, and A4 or US Letter paper.',
                    'Click Convert to PDF to download one combined, crisp PDF immediately.',
                ],
                'preserved' => 'Preserves original image sharpness and full aspect ratio without cropping. Multiple uploaded images are assembled sequentially into pages of a single clean PDF.',
                'limitations' => 'Very large camera RAW/oversized uploads are scaled cleanly to fit your selected printable paper size so file size stays fast to email.',
                'faqs' => [
                    ['q' => 'Can I combine multiple JPG and PNG files into one PDF?', 'a' => 'Yes. Select multiple images at once and Formnexa merges them cleanly into one multi-page PDF.'],
                    ['q' => 'Are watermarks added to converted PDFs?', 'a' => 'Never. Every PDF exports clean without watermarks.'],
                    ['q' => 'Are my images safe and private?', 'a' => 'Files are converted immediately on-demand and temporary processing files are discarded once delivered.'],
                    ['q' => 'Does JPG to PDF work on mobile phones?', 'a' => 'Yes, upload photos straight from your mobile camera roll and download the PDF in seconds.'],
                ],
                'related' => [
                    ['PDF to JPG Converter', '/convert/pdf-to-jpg'],
                    ['Word to PDF Converter', '/convert/word-to-pdf'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'word-to-pdf' => [
                'steps' => [
                    'Select your `.docx`, `.rtf`, `.odt`, or `.txt` file.',
                    'Let Formnexa convert headings, paragraphs, lists, and tables into a PDF layout.',
                    'Download your locked-layout PDF ready for printing or job applications.',
                ],
                'preserved' => 'Keeps structured headings, bullet lists, paragraphs, and standard tables cleanly legible across any operating system.',
                'limitations' => 'Exotic proprietary desktop fonts not embedded inside the source Word file fall back to crisp standard web-safe sans/serif typography.',
                'faqs' => [
                    ['q' => 'Why convert a Word CV or contract to PDF before sending?', 'a' => 'PDF locks margins and line spacing so recipients see the exact layout regardless of Word version or phone viewer.'],
                    ['q' => 'Is this Word to PDF converter free?', 'a' => 'Yes, 100% free with no sign-up or daily page traps.'],
                    ['q' => 'Does it keep bold text and tables?', 'a' => 'Yes, standard DOCX headings, bold runs, and tabular blocks render cleanly in the output PDF.'],
                    ['q' => 'Need to edit the PDF back in Word later?', 'a' => 'Use our reverse PDF to Word tool anytime.'],
                ],
                'related' => [
                    ['PDF to Word Converter', '/convert/pdf-to-word'],
                    ['ATS Resume Builder', '/resume-builder'],
                    ['Excel to PDF Converter', '/convert/excel-to-pdf'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'powerpoint-to-pdf' => [
                'steps' => [
                    'Upload your `.pptx` presentation deck.',
                    'Formnexa renders slides cleanly onto widescreen PDF handouts.',
                    'Download the share-ready PDF without needing PowerPoint installed.',
                ],
                'preserved' => 'Preserves slide sequence, titles, bullet content, and clean 16:9 widescreen handout framing.',
                'limitations' => 'Slide animations, transitions, and embedded video clips become static printable slide frames in PDF format.',
                'faqs' => [
                    ['q' => 'Do recipients need PowerPoint to view the exported deck?', 'a' => 'No. Any browser, phone, or PDF reader opens your converted presentation cleanly.'],
                    ['q' => 'Are slides formatted in widescreen?', 'a' => 'Yes, slides export cleanly onto widescreen pages ideal for reading or PDF handouts.'],
                    ['q' => 'Is PowerPoint to PDF free without watermarks?', 'a' => 'Yes, zero watermarks and no sign-up required.'],
                    ['q' => 'Can I convert a PDF back into PowerPoint slides?', 'a' => 'Yes, try our PDF to PowerPoint converter.'],
                ],
                'related' => [
                    ['PDF to PowerPoint Converter', '/convert/pdf-to-powerpoint'],
                    ['Word to PDF Converter', '/convert/word-to-pdf'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'excel-to-pdf' => [
                'steps' => [
                    'Choose your `.xlsx`, `.xls`, or `.csv` workbook.',
                    'Pick Landscape or Portrait orientation and toggle gridlines on or off.',
                    'Download a clean, readable spreadsheet PDF.',
                ],
                'preserved' => 'Keeps spreadsheet worksheets, numeric values, formulas evaluated as data, headers, and optional cell gridlines legible.',
                'limitations' => 'Extremely wide 20+ column sheets work best in Landscape mode so columns have generous breathing room.',
                'faqs' => [
                    ['q' => 'Should I pick Landscape or Portrait for Excel sheets?', 'a' => 'Use Landscape for wide financial ledgers and Portrait for narrow lists or price tables.'],
                    ['q' => 'Can I show or hide cell gridlines?', 'a' => 'Yes, toggle the Include Table Gridlines option right in the sidebar before converting.'],
                    ['q' => 'Are CSV files also supported?', 'a' => 'Yes, `.xlsx`, `.xls`, and `.csv` spreadsheets convert cleanly to PDF.'],
                    ['q' => 'Need to extract tables back from a PDF?', 'a' => 'Try our reverse PDF to Excel converter.'],
                ],
                'related' => [
                    ['PDF to Excel Converter', '/convert/pdf-to-excel'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'html-to-pdf' => [
                'steps' => [
                    'Paste raw HTML/CSS code, upload an `.html` file, or enter a public webpage URL.',
                    'Review or load a sample template if testing styles.',
                    'Click Convert HTML to PDF for a crisp downloadable document.',
                ],
                'preserved' => 'Renders clean semantic HTML typography, CSS borders, rounded cards, and structured page layouts directly into PDF.',
                'limitations' => 'External authenticated URLs behind login walls cannot be fetched; for gated dashboards, paste the rendered HTML source directly.',
                'faqs' => [
                    ['q' => 'Can I paste inline CSS styles with my HTML?', 'a' => 'Yes, `<style>` blocks and inline CSS styling are respected during PDF generation.'],
                    ['q' => 'Is there a ready sample template to test?', 'a' => 'Yes, click Load Sample Invoice HTML to test layout conversion in one click.'],
                    ['q' => 'Does HTML to PDF add any header/footer watermarks?', 'a' => 'No watermarks are added.'],
                    ['q' => 'Can I convert the generated PDF into JPG previews?', 'a' => 'Yes, pass any PDF through our PDF to JPG tool.'],
                ],
                'related' => [
                    ['PDF to JPG Converter', '/convert/pdf-to-jpg'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'pdf-to-jpg' => [
                'steps' => [
                    'Upload any multi-page or single-page `.pdf` file.',
                    'Select fast preview mode or high-DPI crisp page rasterization.',
                    'Download every PDF page directly as `.jpg` images (or multi-page ZIP bundle).',
                ],
                'preserved' => 'Renders visual PDF pages accurately into universal JPG images at crisp resolution ideal for social feeds, slides, and previews.',
                'limitations' => 'JPG converts vector text into flat pixels, so resulting images are not text-editable unless converted to Word instead.',
                'faqs' => [
                    ['q' => 'Does PDF to JPG work for multi-page PDFs?', 'a' => 'Yes, every page renders cleanly so you can save individual pages or the full bundle.'],
                    ['q' => 'Is rendering fast and private?', 'a' => 'Pages are rasterized cleanly in your session without adding any watermarks.'],
                    ['q' => 'Can I reverse JPG images back into a PDF?', 'a' => 'Yes, use our JPG to PDF converter anytime.'],
                    ['q' => 'Is there any sign-up gate?', 'a' => 'No sign-up or email gate required.'],
                ],
                'related' => [
                    ['JPG to PDF Converter', '/convert/jpg-to-pdf'],
                    ['PDF to Word Converter', '/convert/pdf-to-word'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'pdf-to-word' => [
                'steps' => [
                    'Drop your `.pdf` file into the upload box.',
                    'Choose Editable Text mode for rewriting content or Visual Layout mode for snapshot fidelity.',
                    'Click Convert and download your `.docx` file ready for Microsoft Word or Google Docs.',
                ],
                'preserved' => 'Extracts paragraphs, headings, and document flow cleanly from native digital PDFs into editable `.docx` files (and preserves page visuals when Visual Mode is selected).',
                'limitations' => 'Scanned image-only PDFs or complex multi-column magazine brochures may need light spacing tweaks or Visual Mode to preserve exact appearance.',
                'faqs' => [
                    ['q' => 'Which mode should I pick — Editable or Visual?', 'a' => 'Choose Editable Text when you want to edit paragraphs or copy text inside Word; choose Visual Layout when exact visual appearance matters most.'],
                    ['q' => 'Will scanned PDFs become editable text?', 'a' => 'Scanned image PDFs have no embedded text layer, so Visual Mode preserves exact page graphics cleanly without distorting them.'],
                    ['q' => 'Is PDF to Word free without page watermarks?', 'a' => 'Yes, downloaded `.docx` files contain zero watermarks.'],
                    ['q' => 'Need to turn edited Word docs back into PDF?', 'a' => 'Use our Word to PDF converter once your edits are done.'],
                ],
                'related' => [
                    ['Word to PDF Converter', '/convert/word-to-pdf'],
                    ['PDF to Excel Converter', '/convert/pdf-to-excel'],
                    ['PDF to PowerPoint Converter', '/convert/pdf-to-powerpoint'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'pdf-to-powerpoint' => [
                'steps' => [
                    'Select the `.pdf` deck or handout you want to convert.',
                    'Choose between Visual Slide Fidelity or Editable Slide Text.',
                    'Download your `.pptx` PowerPoint file immediately.',
                ],
                'preserved' => 'Maps each PDF page 1-to-1 onto PowerPoint slides cleanly so decks open cleanly in Microsoft PowerPoint, Keynote, or Google Slides.',
                'limitations' => 'Complex overlapping vector illustrations inside PDFs look crispiest when converted with Visual Slide Fidelity mode enabled.',
                'faqs' => [
                    ['q' => 'Are PDF pages split one per PowerPoint slide?', 'a' => 'Yes, every page of your PDF maps cleanly onto its own slide.'],
                    ['q' => 'Can I edit the slide text afterwards?', 'a' => 'Choose Editable Slide Text mode for selectable text boxes, or Visual Fidelity to keep complex graphics intact.'],
                    ['q' => 'Do I need an account to convert PDF to PPTX?', 'a' => 'No account required.'],
                    ['q' => 'Need to convert PPTX back into PDF?', 'a' => 'Use our PowerPoint to PDF tool.'],
                ],
                'related' => [
                    ['PowerPoint to PDF Converter', '/convert/powerpoint-to-pdf'],
                    ['PDF to Word Converter', '/convert/pdf-to-word'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'pdf-to-excel' => [
                'steps' => [
                    'Upload the `.pdf` report, bank ledger, or invoice table.',
                    'Choose whether to place each PDF page on its own worksheet or stack rows onto one sheet.',
                    'Download your clean `.xlsx` workbook.',
                ],
                'preserved' => 'Identifies structured columnar data and tabular lines from digital PDFs and organizes rows cleanly into native Excel cells.',
                'limitations' => 'Scanned image PDFs or irregular multi-line wrapped cells may require light column cleanup after opening in Excel.',
                'faqs' => [
                    ['q' => 'Should I keep one sheet per page or combine all tables?', 'a' => 'Keep one sheet per page when pages represent distinct reports, or combine onto one sheet for multi-page continuous statements.'],
                    ['q' => 'Does it output real `.xlsx` Excel workbooks?', 'a' => 'Yes, native `.xlsx` files open cleanly in Excel, Google Sheets, and LibreOffice Calc.'],
                    ['q' => 'Is there a file watermark or row limit watermark?', 'a' => 'None — full clean spreadsheet exports.'],
                    ['q' => 'Need to convert spreadsheets back to PDF?', 'a' => 'Try our Excel to PDF converter.'],
                ],
                'related' => [
                    ['Excel to PDF Converter', '/convert/excel-to-pdf'],
                    ['PDF to Word Converter', '/convert/pdf-to-word'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
            'pdf-to-pdfa' => [
                'steps' => [
                    'Upload your standard `.pdf` contract, thesis, or compliance document.',
                    'Pick ISO archival conformance (`PDF/A-1b` or `PDF/A-3b`).',
                    'Download your standardized archival PDF/A document.',
                ],
                'preserved' => 'Embeds standardized sRGB ICC color profile metadata and structured XMP archival conformance markers for long-term institutional storage.',
                'limitations' => 'Interactive JavaScript triggers and password restrictions are stripped by design to meet ISO 19005 archival compliance.',
                'faqs' => [
                    ['q' => 'What is the difference between PDF/A-1b and PDF/A-3b?', 'a' => 'PDF/A-1b enforces baseline ISO visual preservation for legal records, while PDF/A-3b supports newer compliance metadata standards.'],
                    ['q' => 'Why do universities, courts, and government portals ask for PDF/A?', 'a' => 'So archived records continue rendering identically decades later without broken external dependencies.'],
                    ['q' => 'Is PDF to PDF/A free?', 'a' => 'Yes, convert archival records cleanly with zero watermarks.'],
                    ['q' => 'Need to create a standard PDF from Word or JPG first?', 'a' => 'Convert via Word to PDF or JPG to PDF beforehand, then convert to PDF/A.'],
                ],
                'related' => [
                    ['PDF to Word Converter', '/convert/pdf-to-word'],
                    ['Word to PDF Converter', '/convert/word-to-pdf'],
                    ['All 10 PDF Converters', '/convert'],
                ],
            ],
        ];

        return $items[$slug] ?? $items['jpg-to-pdf'];
    }
}
