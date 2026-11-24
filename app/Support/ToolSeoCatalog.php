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
                    ['q' => 'How can I mail invoices to foreign countries or customers?', 'a' => 'To mail invoices to foreign countries or international customers: 1) Select the client\'s home currency (USD, EUR, GBP, SGD, AED) in the studio, 2) Include their overseas address, country code, and VAT/EIN/Tax ID, 3) Add your international SWIFT/BIC or IBAN bank details or a digital payment link, and 4) Download a watermark-free PDF to email or mail invoices to foreign countries directly.'],
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
                'overview_badge' => 'Proof of Payment & Bookkeeping',
                'overview_title' => 'How to Make a Receipt Online: Cash, Card & Wire Transfer Proof of Payment',
                'overview' => '<p>A <strong>payment receipt</strong> (proof of purchase or payment voucher) is an official document issued by a merchant, service provider, or freelancer confirming that funds have been received in full or in part. While an invoice is a request for payment, a receipt acts as conclusive legal and financial evidence that an obligation has been satisfied.</p><p>Creating a professional receipt takes under 60 seconds with Formnexa. You can itemize purchased goods, reference an outstanding invoice number, record payment channels (such as cash, credit card, bank wire, or online payment links), and export a clean vector PDF stamped with <em>"Paid in Full"</em>.</p>',
                'steps' => [
                    'Set your business or merchant details, add your brand logo, and enter contact info.',
                    'Enter the customer name and unique sequential receipt number (`REC-`).',
                    'List purchased items or settled invoice references with amounts, discounts, and taxes.',
                    'Specify the payment method (Cash, Card, Bank Wire) and export your clean Paid PDF receipt.',
                ],
                'checklist' => [
                    'Merchant name, address, tax ID (EIN/VAT/NTN) & contact details',
                    'Unique receipt number (`REC-`), payment date & transaction reference',
                    'Customer billing name and company address',
                    'Itemized breakdown of products, hours, or settled invoice ID',
                    'Tax collected, payment method, total amount received & Paid in Full confirmation',
                ],
                'comparison_title' => 'Payment Receipt vs. Invoice & Sales Order',
                'comparison_body' => 'An Invoice is a request for money still owed, and a Sales Order confirms a purchase before delivery. In contrast, a Receipt is formal proof that payment has already arrived. Issuing prompt receipts prevents duplicate collections and helps clients claim legitimate business expenses.',
                'tips' => 'Reference the original invoice number in the receipt notes line so both your bookkeeper and client can reconcile transactions cleanly.',
                'example_title' => 'Payment Receipt Example: Freelance / Contractor Settlement',
                'example' => '<p><strong>Recipient / Customer:</strong> BlueWave Media LLC | <strong>Merchant:</strong> Elena Vance Digital Consultancy</p><p><strong>Receipt #REC-7801:</strong> Payment received for settling Invoice #INV-502 (Q4 Web Architecture Sprint). Amount Paid: <strong>$1,200.00 USD</strong>. Payment Method: Direct Bank Wire (Ref: TXN-892401). Tax Collected: $0.00 (B2B Export). Balance Due: <strong>$0.00 (PAID IN FULL)</strong>. Issued within minutes of bank notification and emailed directly to customer accounting.</p>',
                'faqs' => [
                    ['q' => 'How do I make a receipt for cash payments?', 'a' => 'Enter your business details, customer name, cash amount received, and select "Cash" in the payment notes. The exported PDF serves as a legitimate paper or digital cash receipt.'],
                    ['q' => 'Does the receipt mark the balance as Paid?', 'a' => 'Yes. The Receipt Generator defaults to paid status and clearly displays "Paid in Full" with zero balance remaining.'],
                    ['q' => 'Can freelancers use this for card, wire, or bank transfers?', 'a' => 'Yes. Note the transaction ID, Stripe/PayPal receipt code, or bank transfer reference in the notes field before downloading the PDF.'],
                    ['q' => 'Is there a limit on how many receipts I can generate?', 'a' => 'No. Generate and download as many clean receipt PDFs as you need with zero fees and no watermark.'],
                    ['q' => 'Is a PDF receipt valid for business tax deductions?', 'a' => 'Yes. Itemized receipts detailing the merchant name, date, payment amount, and service description fulfill standard IRS, HMRC, and global tax deduction documentation criteria.'],
                    ['q' => 'Can I create a receipt on my phone?', 'a' => 'Yes. The Formnexa studio is fully responsive on mobile and tablet devices, allowing field workers to issue on-the-spot receipts.'],
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
                'overview_badge' => 'Accounting & Invoicing Guide',
                'overview_title' => 'What Is a Credit Note, When to Issue One & Accounting Ledger Rules',
                'overview' => '<p>A <strong>credit note</strong> (also called a <em>credit memo</em> or <em>credit memorandum</em>) is an official commercial accounting document issued by a seller to a buyer to reduce, offset, or completely cancel the balance owed on a previously issued sales invoice. In standard double-entry bookkeeping, a credit note credits Accounts Receivable (reducing the customer\'s outstanding debt) and debits Sales Revenue or Sales Returns, keeping both parties\' financial ledgers balanced to the penny.</p><p>Why can\'t you simply delete or edit a finalized invoice? In modern accounting and under tax authorities globally (such as the IRS in the United States, HMRC in the United Kingdom, Federal Tax Authorities in Europe and the Middle East, and FBR in Pakistan), finalized invoices cannot simply be altered or erased once sent to a client or posted to an accounting ledger. Deleting an invoice creates broken gaps in mandatory sequential numbering, distorts reported VAT or sales tax liabilities, and triggers audit red flags. Issuing a formal credit note preserves a complete, transparent audit trail that tax inspectors and auditors expect.</p><p>There are four common business scenarios where issuing a credit note is required:</p><ol class="space-y-1.5 pl-5 list-decimal text-xs sm:text-sm"><li><strong>Product Returns &amp; Damaged Shipments:</strong> When a customer returns goods because they arrived damaged, defective, or in incorrect quantities, a credit note reduces their bill by the exact value of the returned stock plus associated sales tax.</li><li><strong>Pricing &amp; Calculation Errors:</strong> If an invoice applied an outdated hourly rate, charged incorrect quantities, or failed to apply an agreed promotional discount, a credit note corrects the billing difference without cancelling the entire transaction.</li><li><strong>Post-Sale Rebates &amp; Volume Deductions:</strong> When clients hit agreed quarterly volume milestones or early-payment discount terms, credit notes record the rebate against future billings.</li><li><strong>Cancelled Services &amp; Scope Adjustments:</strong> If a freelance project or retainer agreement is terminated before completion, a credit note officially releases the client from paying unperformed project milestones.</li></ol><p class="text-[11px] text-[#68736d] italic mt-2">Notice: Tax legislation and invoicing compliance vary by jurisdiction. This guide provides general commercial best practices and does not constitute formal tax or legal counsel.</p>',
                'steps' => [
                    'Enter your supplier or seller details, business logo, and the customer receiving the credit.',
                    'Reference the original invoice number (`INV-`) and select the currency.',
                    'Itemize the returned goods, cancelled services, or agreed rate adjustments with tax percentages.',
                    'Download a clean, audit-compliant Credit Note PDF with zero watermarks or share the live link.',
                ],
                'checklist' => [
                    'Unique Credit Note number (`CRN-`) & issue date',
                    'Direct reference to the original Invoice number (`INV-`) being credited',
                    'Seller business details (Name, address, NTN/STRN/VAT or Tax ID)',
                    'Customer billing details and account reference',
                    'Clear description of reason for credit (return, overbilling, discount)',
                    'Itemized breakdown of credited quantities, unit prices & tax adjustments',
                    'Total net credit amount reducing the accounts receivable balance',
                ],
                'comparison_title' => 'Credit Note vs. Standard Invoice & Debit Note',
                'comparison_body' => 'While an Invoice increases a customer\'s balance owed and a Debit Note is sent by a buyer requesting a credit, a Credit Note is issued by the seller to officially forgive or reduce debt. It can be applied against an outstanding invoice or held as store credit toward future orders.',
                'tips' => 'Follow up a partial credit note with an updated Account Statement so the client sees their exact remaining net balance.',
                'example_title' => 'Credit Note Example Scenario: B2B Service Adjustment',
                'example' => '<p><strong>Supplier:</strong> Apex Digital Studios | <strong>Client:</strong> Veritas Logistics Inc.</p><p><strong>Background:</strong> Apex issued Invoice <code>#INV-1082</code> for <strong>$2,500.00 USD</strong> covering 10 marketing banners and web assets. Veritas notifies Apex that 2 banners were cancelled by mutual agreement prior to production, valued at $300.00 plus $30.00 sales tax.</p><p><strong>Accounting Action:</strong> Rather than tampering with Invoice #INV-1082, Apex generates Credit Note <code>#CRN-041</code> referencing <code>#INV-1082</code> with description <em>"Credit adjustment for 2 cancelled display banners per mutual agreement"</em> for <strong>$330.00</strong>. Veritas applies #CRN-041 against the invoice and pays the remaining $2,170.00. Both companies maintain perfectly matched general ledger accounts with zero audit discrepancy.</p>',
                'faqs' => [
                    ['q' => 'When should I issue a credit note instead of editing an invoice?', 'a' => 'Whenever an invoice has already been sent to a customer, recorded in accounting software, or submitted for tax filing. Tampering with issued invoices breaks sequential audit trails, whereas a credit note cleanly offsets the discrepancy.'],
                    ['q' => 'Does a credit note adjust output tax (VAT/GST/Sales Tax) too?', 'a' => 'Yes. Credit notes should apply the exact tax rate from the original invoice lines. This allows the seller to reclaim previously declared output tax and ensures the buyer reduces their input tax credit accordingly.'],
                    ['q' => 'What is the difference between a credit note and a refund?', 'a' => 'A credit note is an accounting document recording debt reduction. A refund is the physical return of cash or bank transfer. If an invoice was unpaid, the credit note cancels the debt. If already paid, the credit note justifies the cash refund or creates store credit.'],
                    ['q' => 'Can I issue a partial credit note?', 'a' => 'Yes. You can credit specific line items or a partial dollar amount while leaving the rest of the original invoice active and payable.'],
                    ['q' => 'What is the difference between a credit note and a debit note?', 'a' => 'A credit note is issued by a seller to reduce what the buyer owes. A debit note is issued by a buyer to formally request that credit (or by a seller to increase an underbilled invoice).'],
                    ['q' => 'Is this credit memo generator free without watermarks?', 'a' => 'Yes. You can generate, customize, and export unlimited credit note PDFs on Formnexa completely free with zero sign-up and no watermarks.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Account Statement Generator', '/account-statement-generator'],
                    ['Payment Receipt Generator', '/receipt-generator'],
                    ['International Invoicing Guide', '/blog/international-invoice-format-asia-usa-europe-guide'],
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
                'overview_badge' => 'International Trade & Export Billing',
                'overview_title' => 'Proforma Invoice vs. Commercial Invoice: Crucial Differences & Customs Clearance',
                'overview' => '<p>A <strong>proforma invoice</strong> is a preliminary commercial declaration sent by an exporter or supplier to an international buyer before goods are manufactured or dispatched. While it looks virtually identical to a standard invoice, a proforma invoice is technically a binding good-faith commitment: it details the exact goods, quantities, agreed unit pricing, estimated shipping/freight costs, and delivery terms (Incoterms).</p><p>Understanding the distinction between a proforma invoice and a commercial invoice is critical for international trade and bookkeeping:</p><ul class="space-y-1.5 pl-5 list-disc text-xs sm:text-sm"><li><strong>Accounting &amp; Tax Status:</strong> A proforma invoice is <em>never</em> recorded as an accounts receivable asset by the seller or accounts payable by the buyer. It is not a tax demand. The official <em>commercial invoice</em> is issued only when goods are shipped or services delivered, establishing taxable revenue.</li><li><strong>Customs Clearance &amp; Import Permits:</strong> Importers require a proforma invoice to secure foreign exchange allocations from central banks, obtain import permits from customs authorities, and request commercial Letters of Credit (L/C) from international banking institutions.</li><li><strong>Pre-Payment Terms:</strong> In cross-border commerce where suppliers require 30% to 100% advance deposit via SWIFT wire before starting fabrication, the proforma invoice acts as the formal invoice justification for the buyer\'s treasury team to release payment.</li></ul>',
                'steps' => [
                    'Enter exporter/seller details, country, and international buyer consignee info.',
                    'Set Proforma Invoice reference (`PI-`), validity window, and advance wire terms.',
                    'List product descriptions, HS codes/SKUs, quantities, unit values, and freight/insurance costs.',
                    'Select the trade currency (USD, EUR, GBP, AED, SAR, CNY, PKR) and export your customs-ready PDF.',
                ],
                'checklist' => [
                    'Clear "PROFORMA INVOICE" header & unique reference number (`PI-`)',
                    'Exporter and importer full names, registered addresses & contact emails',
                    'Issue date and expiration / validity period (e.g., 30 or 60 days)',
                    'Itemized product descriptions, quantities, HS tariff codes & unit rates',
                    'Agreed Incoterms (such as FOB, CIF, or EXW) and destination port/airport',
                    'Freight, packing, insurance charges & total estimated landed quotation',
                    'Seller SWIFT/BIC code, IBAN, and advance payment instructions',
                ],
                'comparison_title' => 'Proforma Invoice vs. Commercial Invoice & Quotation',
                'comparison_body' => 'A Quote is an early price estimation that may change. A Proforma Invoice locks in the firm price, specifications, and shipping terms so the buyer can arrange bank financing and import permits. Once goods dispatch, the seller issues the final Commercial Invoice for official customs duty and bookkeeping.',
                'tips' => 'Always specify Incoterms (such as "CIF London Gateway" or "FOB Karachi") and an expiration date in the notes to protect against ocean freight rate fluctuations.',
                'example_title' => 'Proforma Invoice Example: International B2B Manufacturing Order',
                'example' => '<p><strong>Exporter:</strong> Precision Castings Ltd (Exporting from Sialkot/Karachi) | <strong>Buyer:</strong> Nordic Industrial Oy (Helsinki, Finland)</p><p><strong>Proforma Invoice #PI-2026-92:</strong> 500 units Stainless Flange Assemblies (HS Tariff Code: <code>7307.21</code>) @ $14.00/unit = <strong>$7,000.00 USD</strong>. Sea Freight &amp; Marine Cargo Insurance (CIF Helsinki Port) = <strong>$650.00 USD</strong>. Total Proforma Landed Value: <strong>$7,650.00 USD</strong>. Terms: 50% advance wire deposit to initiate CNC tooling, 50% prior to bill of lading surrender. Nordic Industrial presents #PI-2026-92 to their bank to open the wire transfer without delay.</p>',
                'faqs' => [
                    ['q' => 'Is a proforma invoice legally binding?', 'a' => 'While not an official tax invoice, a proforma invoice represents a binding commercial offer once accepted by the buyer. The seller is committed to delivering the specified goods at the quoted prices within the stated validity window.'],
                    ['q' => 'Can a proforma invoice be used for customs clearance?', 'a' => 'Customs authorities frequently accept a proforma invoice for preliminary import declaration or when goods are shipped as free commercial samples or warranty replacements. However, commercial shipments typically require the final commercial invoice upon customs arrival.'],
                    ['q' => 'How do I convert a proforma invoice into a final commercial invoice?', 'a' => 'Once manufacturing is complete and goods dispatch, duplicate your proforma invoice in Formnexa, switch the document type to Invoice (`INV-`), update the final shipping date and bill of lading number, and export the commercial invoice.'],
                    ['q' => 'Can I include freight, insurance, and Incoterms?', 'a' => 'Yes. Add dedicated lines for ocean/air freight and marine insurance, and declare agreed Incoterms (FOB, CIF, CFR, EXW, DDP) directly in the terms and notes fields.'],
                    ['q' => 'Which currencies does the proforma generator support?', 'a' => 'Formnexa supports 24 global trade currencies including USD, EUR, GBP, AED, SAR, CAD, AUD, CNY, and PKR.'],
                    ['q' => 'Is this proforma generator free with no watermark?', 'a' => 'Yes, 100% free with unlimited PDF downloads and zero Formnexa watermarks.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Quote Generator', '/quote-generator'],
                    ['Purchase Order Generator', '/purchase-order-generator'],
                    ['Packing Slip Generator', '/packing-slip-generator'],
                    ['International Invoicing Guide', '/blog/international-invoice-format-asia-usa-europe-guide'],
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
                'overview_badge' => 'Field Service & Maintenance Management',
                'overview_title' => 'Free Online Work Order Generator: Field Service Guide & Core Sections',
                'overview' => '<p>A <strong>work order</strong> (also known as a job ticket, service authorization, or maintenance order) is an operational document that formally authorizes, tracks, and documents service tasks, repairs, or maintenance work performed for a client. Unlike a preliminary estimate or a post-job invoice, a work order functions as the active task manifest that coordinates field technicians, records consumed parts and labor hours, and secures customer sign-off upon completion.</p><p>Essential sections of a professional work order include:</p><ul class="space-y-1.5 pl-5 list-disc text-xs sm:text-sm"><li><strong>Work Order Number &amp; Schedule:</strong> A unique tracking ID (`WO-`), scheduled date, and target completion window.</li><li><strong>Client &amp; Site Location:</strong> Physical job site address, suite/access codes, and primary on-site contact person.</li><li><strong>Task Scope &amp; Instructions:</strong> Detailed breakdown of work authorized by the client (diagnostic checks, routine maintenance, emergency repairs, or installations).</li><li><strong>Labor Hours &amp; Rates:</strong> Itemized technician hours, hourly billing rates, and specialist labor categories.</li><li><strong>Materials &amp; Replacement Parts:</strong> SKUs, descriptions, and quantities of parts installed or consumed during the service call.</li><li><strong>Customer Authorization Signature:</strong> Client sign-off acknowledging that the described work has been completed to satisfaction.</li></ul>',
                'steps' => [
                    'Enter service contractor details, logo, and the customer job site location.',
                    'Summarize the maintenance or repair scope with clear task descriptions.',
                    'Add technician labor hours, hourly rates, and installed replacement parts.',
                    'Capture on-site customer signature digitally and download your free work order PDF.',
                ],
                'checklist' => [
                    'Unique Work Order ID (`WO-`), issue date & scheduled service window',
                    'Customer contact name, phone number & physical job site address',
                    'Detailed scope of repair, maintenance, or installation tasks',
                    'Technician labor hours, rates, and specialist technician assignment',
                    'Itemized materials, hardware, and replacement parts with unit pricing',
                    'Special hazard, safety, or access notes for field crews',
                    'Client sign-off signature confirming authorization and completion',
                ],
                'comparison_title' => 'Work Order vs. Job Estimate & Invoice',
                'comparison_body' => 'A Job Estimate provides price quotes before work begins. A Work Order authorizes technicians to execute the job and logs actual hours and materials used. Once work is signed off, the completed work order is converted into an Invoice to bill the client.',
                'tips' => 'Capture the customer\'s digital signature directly on a mobile tablet or smartphone upon job completion, then download and email the finalized PDF on the spot.',
                'example_title' => 'Work Order Example: HVAC Maintenance & Motor Replacement',
                'example' => '<p><strong>Service Company:</strong> Polar Air Mechanical Services | <strong>Client Site:</strong> Oakridge Medical Center (Suite 200)</p><p><strong>Work Order #WO-408:</strong> Scheduled rooftop condenser maintenance and fan motor replacement. Labor: 3.5 technician hours @ $95.00/hr = <strong>$332.50</strong>. Materials: 1x 1/3 HP Condenser Fan Motor (SKU: MOT-331) = <strong>$185.00</strong>; 1x Dual Run Capacitor 45/5 MFD = <strong>$32.00</strong>. Total Job Value: <strong>$549.50</strong>. Client facility manager signs authorization pad on tablet; signed PDF exported immediately to initiate warranty records and billing.',
                'faqs' => [
                    ['q' => 'Is this work order generator free online?', 'a' => 'Yes. Formnexa provides a completely free online work order generator with zero account requirements, unlimited PDF downloads, and no watermarks.'],
                    ['q' => 'Can technicians use this on mobile phones or tablets in the field?', 'a' => 'Yes. The entire studio is responsive and includes a touch-friendly digital signature pad so contractors can obtain client signatures on-site.'],
                    ['q' => 'What is the difference between a work order and an invoice?', 'a' => 'A work order authorizes and tracks the physical performance of a job (tasks, hours, and materials). An invoice is the formal financial demand sent to collect payment once work is complete.'],
                    ['q' => 'Can I separate parts and labor on a work order?', 'a' => 'Yes. Add distinct line items for technician labor hours and physical hardware or replacement parts to provide 100% billing transparency.'],
                    ['q' => 'How do I convert a completed work order into an invoice?', 'a' => 'In Formnexa, duplicate or adjust your completed work order, change the document title from Work Order to Invoice (`INV-`), attach payment terms, and download the final bill.'],
                    ['q' => 'Are my saved work orders stored securely?', 'a' => 'Yes. Drafts automatically cache in your browser storage so you never lose entered field details.'],
                ],
                'related' => [
                    ['Timesheet Generator', '/timesheet-generator'],
                    ['Estimate Generator', '/estimate-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Purchase Order Generator', '/purchase-order-generator'],
                ],
            ],
            'account-statement-generator' => [
                'overview_badge' => 'Receivables Management & Reconciliation',
                'overview_title' => 'Customer Account Statements: Clarifying Page Intent, Aging Buckets & PDF Statements',
                'overview' => '<p>A <strong>customer account statement</strong> (often searched as a <em>PDF statement</em> or <em>billing statement</em>) is an accounting ledger summary sent periodically to a client. Unlike a single invoice that demands payment for a specific order, an account statement provides a complete bird\'s-eye view of all transactional activity over a defined period (such as the past month or quarter): opening balance, newly issued invoices, payments received, credit notes applied, and the closing net balance due.</p><p>Account statements are indispensable for managing accounts receivable and preventing overdue invoices from slipping through the cracks. They typically group outstanding amounts into chronological aging buckets:</p><ul class="space-y-1.5 pl-5 list-disc text-xs sm:text-sm"><li><strong>Current (0–30 Days):</strong> Invoices issued recently within standard agreed credit terms (such as Net 30).</li><li><strong>31–60 Days Past Due:</strong> Invoices requiring polite automated reminder statements to client accounts payable teams.</li><li><strong>61–90 Days Past Due:</strong> Seriously delinquent balances requiring direct phone follow-up and formal notification.</li><li><strong>90+ Days Past Due:</strong> Escalated collection accounts where further credit or product shipments are paused.</li></ul>',
                'steps' => [
                    'Fill in your business billing details, contact info, and customer account name.',
                    'Set the statement period (e.g., Oct 1 to Oct 31) and unique Statement ID (`STMT-`).',
                    'Itemize recent invoices, applied payments, or credit memo adjustments across the period.',
                    'Verify the outstanding net balance due and export a clean, printable PDF statement.',
                ],
                'checklist' => [
                    'Statement period date range (Start Date & End Date) & Statement ID (`STMT-`)',
                    'Biller business details (Logo, address, tax registration & payment instructions)',
                    'Customer billing contact details and unique account number',
                    'Chronological itemized rows listing Invoice IDs, payment receipts, and credit notes',
                    'Previous payments received (`Amount Paid`) deducted from total charges',
                    'Closing net balance due and online payment link for quick remittance',
                ],
                'comparison_title' => 'Account Statement vs. Single Invoice',
                'comparison_body' => 'An Invoice bills for one specific order or milestone. An Account Statement summarizes multiple invoices, payments, and credits over a month or quarter to show total balance owed.',
                'tips' => 'Send account statements on the 1st of every month to retainer and wholesale accounts to catch overdue bills early.',
                'example_title' => 'Account Statement Example: Monthly Retainer Client',
                'example' => '<p><strong>Billing Company:</strong> Apex Logistics Inc. | <strong>Client:</strong> Sterling Retail Group (Account #ACT-402)</p><p><strong>Statement Period:</strong> Oct 1 – Oct 31, 2026 (Statement #STMT-1031).<br>1) Oct 05: Invoice #INV-801 ($2,200.00) — Paid in full on Oct 12 (-$2,200.00).<br>2) Oct 18: Invoice #INV-820 ($1,850.00) — Unpaid.<br>3) Oct 24: Credit Note #CRN-012 (-$150.00 adjustment for freight rebate).<br><strong>Closing Net Balance Due: $1,700.00 USD</strong>. Client accounting department reconciles in seconds and settles via attached payment link.</p>',
                'faqs' => [
                    ['q' => 'Can I generate a PDF statement for my customers?', 'a' => 'Yes. Formnexa\'s account statement generator creates clean, printable PDF statements detailing all invoices, payments, and remaining balances with zero watermark.'],
                    ['q' => 'Can I show partial payments already received?', 'a' => 'Yes. Enter received payments in the Amount Paid field so the statement highlights only the net balance due.'],
                    ['q' => 'Can I include overdue invoice numbers on individual lines?', 'a' => 'Yes, list each invoice date and number directly in line-item descriptions.'],
                    ['q' => 'Does it support all major currencies?', 'a' => 'Yes, 24 currencies including USD, EUR, GBP, and PKR are supported out of the box.'],
                    ['q' => 'When should I send an account statement?', 'a' => 'Best practice is to send customer statements on the 1st of every month or at the close of every billing cycle to accelerate accounts receivable collection.'],
                    ['q' => 'Do clients need a Formnexa account to view a statement link?', 'a' => 'No, share links and PDFs open cleanly for any recipient.'],
                ],
                'related' => [
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['Payment Receipt Generator', '/receipt-generator'],
                    ['Credit Note Generator', '/credit-note-generator'],
                ],
            ],
            'packing-slip-generator' => [
                'overview_badge' => 'Warehouse & Parcel Fulfillment',
                'overview_title' => 'Packing Slip & Parcel Slip Generator: Shipping Manifests & Delivery Notes',
                'overview' => '<p>A <strong>packing slip</strong> (also widely referred to as a <em>parcel slip</em>, <em>package dispatch slip</em>, <em>shipping manifest</em>, or <em>delivery note</em>) is a physical document placed inside or securely affixed outside a shipping carton or pallet. It itemizes every SKU, product name, variation, and physical quantity enclosed within that specific package.</p><p>Why is a parcel slip essential for modern shipping and e-commerce?</p><ul class="space-y-1.5 pl-5 list-disc text-xs sm:text-sm"><li><strong>Warehouse Pick &amp; Pack Accuracy:</strong> Fulfillment teams use the parcel slip to cross-reference physical inventory before taping cartons, drastically lowering return rates caused by missing items.</li><li><strong>Receiving &amp; Customs Verification:</strong> Customers and freight carriers verify delivered goods against the enclosed parcel slip upon arrival before signing carrier delivery receipts.</li><li><strong>Gift Shipments &amp; Blind Drop-Shipping:</strong> When orders are gifts or drop-shipped from a third-party manufacturer, packing slips display SKUs and quantities while concealing wholesale pricing.</li></ul>',
                'steps' => [
                    'Enter sender warehouse or brand address and recipient delivery destination.',
                    'Include the customer purchase order (`PO-`) or Sales Order reference (`SO-`).',
                    'List every SKU, item description, and shipped quantity (`Qty`) inside manifest rows.',
                    'Download or print your crisp Packing Slip / Parcel Slip PDF to place inside the parcel.',
                ],
                'checklist' => [
                    'Ship-From warehouse location & Ship-To recipient delivery address',
                    'Order reference number (`SO-`/`PO-`), dispatch date & package count (e.g. Box 1 of 2)',
                    'Exact SKU codes, item descriptions & verified physical quantities per item',
                    'Carrier tracking number, delivery instructions & warehouse inspection initials',
                    'Optional pricing concealment for gift orders and blind drop-shipments',
                ],
                'comparison_title' => 'Packing Slip vs. Invoice & Sales Order',
                'comparison_body' => 'While an Invoice and Sales Order focus on payment totals and taxes, a Packing Slip focuses on physical fulfillment accuracy so warehouse staff and receiving customers can verify every item in the box.',
                'tips' => 'Leave unit prices at `0` if sending a gift order or blind drop-shipment where the recipient should not see wholesale pricing.',
                'example_title' => 'Packing Slip Example: Multi-Item Parcel Dispatch',
                'example' => '<p><strong>Fulfillment Center:</strong> Metro Logistics Hub (Bay 4) | <strong>Recipient:</strong> Horizon Bookshop &amp; Café</p><p><strong>Parcel Slip #PS-5509:</strong> Order Ref: #SO-8821 | Package: Box 1 of 2.<br>1) 25x Hardcover Journals (SKU: JRN-01)<br>2) 50x Gel Pen Refill Packs (SKU: PEN-04)<br>3) 10x Acrylic Desk Organizers (SKU: ORG-12)<br>Total Units in Carton: 85. Inspected by Picker #12. Prices hidden per drop-ship fulfillment protocol. E-commerce delivery note ready for insertion into shipping carton.</p>',
                'faqs' => [
                    ['q' => 'Is a packing slip the same as a parcel slip?', 'a' => 'Yes. "Packing slip", "parcel slip", "dispatch note", and "shipping manifest" are interchangeable terms describing the physical itemized document enclosed inside a parcel.'],
                    ['q' => 'Should a packing slip show product prices?', 'a' => 'Prices are optional. Standard B2B orders often include prices, whereas gift orders and blind drop-shipments typically omit pricing to focus purely on SKU descriptions and quantities.'],
                    ['q' => 'Can I print multiple packing slips quickly for warehouse batches?', 'a' => 'Yes, click Print or Download PDF directly from the studio for high-resolution vector printouts.'],
                    ['q' => 'Can I customize column labels to match my warehouse terms?', 'a' => 'Yes, rename columns to SKU, Box, Weight, or Unit directly in the studio table headers.'],
                    ['q' => 'What is the difference between a purchase order, sales order, and packing slip?', 'a' => 'A Purchase Order is sent by the buyer requesting goods. A Sales Order is sent by the seller confirming the order. A Packing Slip is packed inside the box verifying what was physically shipped.'],
                    ['q' => 'Is this packing slip generator free with no watermarks?', 'a' => 'Yes, completely free with unlimited PDF downloads and zero watermarks.'],
                ],
                'related' => [
                    ['Sales Order Generator', '/sales-order-generator'],
                    ['Purchase Order Generator', '/purchase-order-generator'],
                    ['Free Invoice Generator', '/invoice-generator'],
                    ['PO vs SO vs Packing Slip Guide', '/blog/purchase-order-vs-sales-order-vs-packing-slip'],
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
                    ['q' => 'How can I convert PDF to DOCX without losing formatting?', 'a' => 'Formnexa reconstructs paragraphs, headings, tables, and alignment so your converted Word (.docx) file matches the original PDF layout cleanly without scrambling.'],
                    ['q' => 'Which mode should I pick — Editable or Visual?', 'a' => 'Choose Editable Text when you want to edit paragraphs or copy text inside Word; choose Visual Layout when exact visual appearance matters most.'],
                    ['q' => 'Will scanned PDFs become editable text?', 'a' => 'Scanned image PDFs have no embedded text layer, so Visual Mode preserves exact page graphics cleanly without distorting them.'],
                    ['q' => 'Is PDF to Word free without page watermarks?', 'a' => 'Yes, downloaded `.docx` files contain zero watermarks.'],
                    ['q' => 'Need to turn edited Word docs back into PDF?', 'a' => 'Use our Word to PDF converter once your edits are done.'],
                ],
                'related' => [
                    ['PDF to Word Formatting Guide', '/blog/convert-pdf-to-editable-word-without-losing-formatting'],
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
