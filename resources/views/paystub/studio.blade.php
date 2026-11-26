@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#fafbfa] text-[#17211b] py-8 sm:py-12"
     x-data="paystubStudio(@js($defaultData))"
     x-cloak>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- Top Badge --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc]">
                <span class="w-2 h-2 rounded-full bg-[#184f3a]"></span>
                Free Paystub &amp; Salary Slip Maker &bull; Instant PDF &bull; No Watermark
            </span>
        </div>

        {{-- Headline & Hero Copy --}}
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black font-display tracking-tight text-[#17211b] mb-3">
                Free Paystub &amp; <span class="text-[#184f3a]">Salary Slip Generator</span>
            </h1>
            <p class="text-base sm:text-lg text-[#55635a] leading-relaxed">
                Create authentic earnings statements, employee payslips, and salary receipts online. Accurate tax and deduction calculations, Year-to-Date (YTD) tracking, and instant watermark-free PDF downloads.
            </p>
        </div>

        {{-- TWO-COLUMN STUDIO --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: EDITING FORMS --}}
            <div class="lg:col-span-6 space-y-6">

                {{-- Currency & Pay Frequency Bar --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-4 sm:p-5 shadow-sm flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-[#3f4a43]">Currency:</label>
                        <select x-model="currency.symbol" class="p-1.5 rounded-xl border border-[#d6ded8] text-xs font-bold bg-[#fbfdfc]">
                            <option value="$">USD / CAD / AUD ($)</option>
                            <option value="€">EUR (€)</option>
                            <option value="£">GBP (£)</option>
                            <option value="Rs ">PKR / INR (Rs)</option>
                            <option value="AED ">AED</option>
                            <option value="SAR ">SAR</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-[#3f4a43]">Frequency:</label>
                        <select x-model="pay_period.frequency" class="p-1.5 rounded-xl border border-[#d6ded8] text-xs font-bold bg-[#fbfdfc]">
                            <option value="Weekly">Weekly (52/yr)</option>
                            <option value="Bi-Weekly">Bi-Weekly (26/yr)</option>
                            <option value="Semi-Monthly">Semi-Monthly (24/yr)</option>
                            <option value="Monthly">Monthly (12/yr)</option>
                        </select>
                    </div>

                    <button type="button" @click="resetToDefaults()"
                            class="text-xs font-bold text-[#184f3a] hover:underline">
                        Reset Defaults
                    </button>
                </div>

                {{-- Company & Employee Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Company Info --}}
                    <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 shadow-sm space-y-3">
                        <div class="flex items-center gap-2 mb-2 font-extrabold text-xs text-[#17211b]">
                            <span class="w-6 h-6 rounded-lg bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center">🏢</span>
                            <span>Employer / Company</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">Company Name</label>
                            <input type="text" x-model="company.name" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">Company Address</label>
                            <textarea x-model="company.address" rows="2" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">EIN / Tax Registration #</label>
                            <input type="text" x-model="company.ein" placeholder="12-3456789" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                    </div>

                    {{-- Employee Info --}}
                    <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 shadow-sm space-y-3">
                        <div class="flex items-center gap-2 mb-2 font-extrabold text-xs text-[#17211b]">
                            <span class="w-6 h-6 rounded-lg bg-[#f0ebff] text-[#6142be] flex items-center justify-center">👤</span>
                            <span>Employee Details</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">Employee Name</label>
                            <input type="text" x-model="employee.name" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">Job Title</label>
                            <input type="text" x-model="employee.title" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">Employee ID</label>
                                <input type="text" x-model="employee.id" placeholder="EMP-102" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-[#3f4a43] mb-1">SSN Last 4</label>
                                <input type="text" x-model="employee.ssn_last4" placeholder="***-**-1234" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pay Dates Card --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-3 font-extrabold text-xs text-[#17211b]">
                        <span class="w-6 h-6 rounded-lg bg-[#fff0e8] text-[#b45309] flex items-center justify-center">📅</span>
                        <span>Pay Period &amp; Dates</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Period Start</label>
                            <input type="date" x-model="pay_period.start_date" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Period End</label>
                            <input type="date" x-model="pay_period.end_date" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-[#3f4a43] mb-1">Payment Date</label>
                            <input type="date" x-model="pay_period.pay_date" class="w-full p-2 rounded-xl border border-[#d6ded8] text-xs">
                        </div>
                    </div>
                </div>

                {{-- Earnings Breakdown Section --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 font-extrabold text-xs text-[#17211b]">
                            <span class="w-6 h-6 rounded-lg bg-[#e8f7ed] text-[#184f3a] flex items-center justify-center">💰</span>
                            <span>Earnings Breakdown</span>
                        </div>
                        <button type="button" @click="addEarningLine()" class="text-xs font-bold text-[#184f3a] hover:underline">+ Add Line</button>
                    </div>

                    <div class="space-y-2 text-xs">
                        <template x-for="(earn, idx) in earnings" :key="idx">
                            <div class="grid grid-cols-12 gap-2 items-center bg-[#f9faf9] p-2.5 rounded-xl border border-[#e8ece9]">
                                <div class="col-span-4">
                                    <input type="text" x-model="earn.description" placeholder="Description" class="w-full p-1.5 rounded-lg border border-[#d6ded8] text-xs">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" step="0.5" x-model.number="earn.hours" @input="calculateLineAmount(earn)" placeholder="Hours" class="w-full p-1.5 rounded-lg border border-[#d6ded8] text-xs">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" step="0.01" x-model.number="earn.rate" @input="calculateLineAmount(earn)" placeholder="Rate" class="w-full p-1.5 rounded-lg border border-[#d6ded8] text-xs">
                                </div>
                                <div class="col-span-3">
                                    <input type="number" step="0.01" x-model.number="earn.amount" @input="recalculateTotals()" placeholder="Total" class="w-full p-1.5 rounded-lg border border-[#d6ded8] text-xs font-bold text-right">
                                </div>
                                <div class="col-span-1 text-center">
                                    <button type="button" @click="removeEarningLine(idx)" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Deductions Breakdown Section --}}
                <div class="bg-white rounded-3xl border border-[#e2e8e3] p-5 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 font-extrabold text-xs text-[#17211b]">
                            <span class="w-6 h-6 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center">🧾</span>
                            <span>Taxes &amp; Deductions</span>
                        </div>
                        <button type="button" @click="addDeductionLine()" class="text-xs font-bold text-[#184f3a] hover:underline">+ Add Deduction</button>
                    </div>

                    <div class="space-y-2 text-xs">
                        <template x-for="(ded, idx) in deductions" :key="idx">
                            <div class="grid grid-cols-12 gap-2 items-center bg-[#f9faf9] p-2.5 rounded-xl border border-[#e8ece9]">
                                <div class="col-span-7">
                                    <input type="text" x-model="ded.description" placeholder="Deduction Name" class="w-full p-1.5 rounded-lg border border-[#d6ded8] text-xs">
                                </div>
                                <div class="col-span-4">
                                    <input type="number" step="0.01" x-model.number="ded.amount" @input="recalculateTotals()" placeholder="Amount" class="w-full p-1.5 rounded-lg border border-[#d6ded8] text-xs font-bold text-right">
                                </div>
                                <div class="col-span-1 text-center">
                                    <button type="button" @click="removeDeductionLine(idx)" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: REAL-TIME PREVIEW & EXPORT ACTIONS --}}
            <div class="lg:col-span-6 lg:sticky lg:top-24 space-y-4">

                {{-- Action Bar --}}
                <div class="bg-white rounded-2xl border border-[#e2e8e3] p-3 shadow-sm flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-[#184f3a]"></span>
                        <span class="text-xs font-bold text-[#17211b]">Live Paystub Preview</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="window.print()"
                                class="px-3.5 py-1.5 rounded-xl border border-[#d6ded8] hover:bg-[#f8fbf9] text-xs font-bold text-[#17211b] transition inline-flex items-center gap-1">
                            <span>Print</span>
                        </button>

                        <button type="button" @click="downloadPdf()"
                                class="px-5 py-1.5 rounded-xl bg-[#184f3a] text-white hover:bg-[#123e2d] text-xs font-bold shadow-sm transition inline-flex items-center gap-1">
                            <span>Download PDF Paystub</span>
                        </button>
                    </div>
                </div>

                {{-- Hidden Export Form for PDF --}}
                <form id="paystubExportForm" method="POST" action="/paystub-generator/pdf" class="hidden">
                    @csrf
                    <input type="hidden" name="payload_json" id="paystubJsonInput">
                </form>

                {{-- Live Real-Time A4 Paystub Canvas --}}
                <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 sm:p-8 shadow-lg text-xs text-[#1e293b] leading-tight font-sans selection:bg-[#184f3a] selection:text-white">
                    
                    {{-- Header Row --}}
                    <div class="flex justify-between items-start border-b-2 border-[#0f172a] pb-4 mb-4">
                        <div>
                            <div class="text-base font-black text-[#0f172a] uppercase tracking-tight" x-text="company.name || 'Company Name'"></div>
                            <div class="text-[11px] text-[#475569] mt-1 whitespace-pre-line" x-text="company.address"></div>
                            <div class="text-[10px] text-[#64748b] mt-0.5" x-show="company.ein" x-text="`EIN: ${company.ein}`"></div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-black text-[#184f3a] uppercase tracking-wider">Earnings Statement</div>
                            <div class="text-[11px] font-bold text-[#64748b]" x-text="`Pay Frequency: ${pay_period.frequency}`"></div>
                            <div class="text-[11px] text-[#334155] mt-1">Pay Date: <strong x-text="pay_period.pay_date"></strong></div>
                        </div>
                    </div>

                    {{-- Meta Information Grid --}}
                    <div class="grid grid-cols-4 gap-2 p-3 bg-[#f8fafc] border border-[#cbd5e1] rounded-xl mb-4 text-[11px]">
                        <div>
                            <div class="text-[9px] font-extrabold uppercase text-[#64748b]">Employee</div>
                            <div class="font-extrabold text-[#0f172a]" x-text="employee.name"></div>
                            <div class="text-[10px] text-[#475569]" x-text="employee.title"></div>
                        </div>
                        <div>
                            <div class="text-[9px] font-extrabold uppercase text-[#64748b]">ID / SSN</div>
                            <div class="font-bold text-[#0f172a]" x-text="employee.id || 'N/A'"></div>
                            <div class="text-[10px] text-[#475569]" x-text="employee.ssn_last4"></div>
                        </div>
                        <div>
                            <div class="text-[9px] font-extrabold uppercase text-[#64748b]">Period</div>
                            <div class="font-bold text-[#0f172a]" x-text="pay_period.start_date"></div>
                            <div class="text-[10px] text-[#475569]" x-text="`to ${pay_period.end_date}`"></div>
                        </div>
                        <div>
                            <div class="text-[9px] font-extrabold uppercase text-[#64748b]">Payment</div>
                            <div class="font-bold text-[#0f172a]" x-text="payment.method"></div>
                            <div class="text-[10px] text-[#475569]" x-text="payment.account_last4"></div>
                        </div>
                    </div>

                    {{-- Side by Side Earnings & Deductions Tables --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        
                        {{-- Earnings --}}
                        <div>
                            <div class="text-[11px] font-extrabold uppercase text-[#0f172a] mb-1.5">Earnings</div>
                            <table class="w-full text-left text-[11px] border border-[#cbd5e1] rounded-lg overflow-hidden">
                                <thead class="bg-[#0f172a] text-white text-[9px] uppercase font-bold">
                                    <tr>
                                        <th class="p-1.5">Item</th>
                                        <th class="p-1.5 text-right">Hrs</th>
                                        <th class="p-1.5 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="earn in earnings" :key="earn.description">
                                        <tr class="border-b border-[#e2e8f0]">
                                            <td class="p-1.5 font-bold" x-text="earn.description"></td>
                                            <td class="p-1.5 text-right text-[#64748b]" x-text="earn.hours > 0 ? earn.hours : '-'"></td>
                                            <td class="p-1.5 text-right font-extrabold" x-text="formatCurrency(earn.amount)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        {{-- Deductions --}}
                        <div>
                            <div class="text-[11px] font-extrabold uppercase text-[#0f172a] mb-1.5">Taxes &amp; Deductions</div>
                            <table class="w-full text-left text-[11px] border border-[#cbd5e1] rounded-lg overflow-hidden">
                                <thead class="bg-[#0f172a] text-white text-[9px] uppercase font-bold">
                                    <tr>
                                        <th class="p-1.5">Deduction</th>
                                        <th class="p-1.5 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="ded in deductions" :key="ded.description">
                                        <tr class="border-b border-[#e2e8f0]">
                                            <td class="p-1.5" x-text="ded.description"></td>
                                            <td class="p-1.5 text-right font-extrabold text-rose-700" x-text="`-${formatCurrency(ded.amount)}`"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    {{-- Totals Summary Box --}}
                    <div class="grid grid-cols-3 border-2 border-[#0f172a] rounded-xl overflow-hidden text-center mb-4">
                        <div class="p-3 bg-[#f1f5f9] border-r border-[#cbd5e1]">
                            <div class="text-[9px] font-extrabold uppercase text-[#475569]">Total Gross Pay</div>
                            <div class="text-sm font-black text-[#0f172a]" x-text="formatCurrency(totals.gross_pay)"></div>
                            <div class="text-[9px] text-[#64748b]" x-text="`YTD: ${formatCurrency(totals.ytd_gross)}`"></div>
                        </div>

                        <div class="p-3 bg-[#f1f5f9] border-r border-[#cbd5e1]">
                            <div class="text-[9px] font-extrabold uppercase text-[#475569]">Total Deductions</div>
                            <div class="text-sm font-black text-rose-700" x-text="`-${formatCurrency(totals.deductions)}`"></div>
                            <div class="text-[9px] text-[#64748b]" x-text="`YTD: -${formatCurrency(totals.ytd_deductions)}`"></div>
                        </div>

                        <div class="p-3 bg-[#e8f7ed]">
                            <div class="text-[9px] font-black uppercase text-[#184f3a]">Net Take-Home Pay</div>
                            <div class="text-base font-black text-[#184f3a]" x-text="formatCurrency(totals.net_pay)"></div>
                            <div class="text-[9px] text-[#184f3a] font-bold" x-text="`YTD: ${formatCurrency(totals.ytd_net_pay)}`"></div>
                        </div>
                    </div>

                    {{-- Check Stub Callout --}}
                    <div class="border-t border-dashed border-[#cbd5e1] pt-3 mt-4 text-[11px] flex justify-between items-center text-[#64748b]">
                        <div>Pay To: <strong class="text-[#0f172a]" x-text="employee.name"></strong></div>
                        <div>Amount: <strong class="text-[#184f3a]" x-text="formatCurrency(totals.net_pay)"></strong></div>
                        <div>Security Code: <strong>FX-AUTHENTIC</strong></div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<script>
function paystubStudio(defaultData) {
    return {
        company: defaultData.company,
        employee: defaultData.employee,
        pay_period: defaultData.pay_period,
        currency: defaultData.currency,
        earnings: defaultData.earnings,
        deductions: defaultData.deductions,
        totals: defaultData.totals,
        payment: defaultData.payment,

        init() {
            this.recalculateTotals();
        },

        calculateLineAmount(earn) {
            if (earn.hours > 0 && earn.rate > 0) {
                earn.amount = Math.round((earn.hours * earn.rate) * 100) / 100;
            }
            this.recalculateTotals();
        },

        addEarningLine() {
            this.earnings.push({
                description: 'Bonus / Additional Pay',
                hours: 0,
                rate: 0,
                amount: 150.00,
                ytd: 1500.00
            });
            this.recalculateTotals();
        },

        removeEarningLine(idx) {
            if (this.earnings.length <= 1) return;
            this.earnings.splice(idx, 1);
            this.recalculateTotals();
        },

        addDeductionLine() {
            this.deductions.push({
                description: 'Other Deduction',
                amount: 50.00,
                ytd: 500.00
            });
            this.recalculateTotals();
        },

        removeDeductionLine(idx) {
            if (this.deductions.length <= 1) return;
            this.deductions.splice(idx, 1);
            this.recalculateTotals();
        },

        recalculateTotals() {
            let gross = 0;
            let ytdGross = 0;
            this.earnings.forEach(e => {
                const amt = parseFloat(e.amount) || 0;
                gross += amt;
                e.ytd = amt * 10;
                ytdGross += e.ytd;
            });

            let dedTotal = 0;
            let ytdDed = 0;
            this.deductions.forEach(d => {
                const amt = parseFloat(d.amount) || 0;
                dedTotal += amt;
                d.ytd = amt * 10;
                ytdDed += d.ytd;
            });

            this.totals.gross_pay = Math.round(gross * 100) / 100;
            this.totals.ytd_gross = Math.round(ytdGross * 100) / 100;
            this.totals.deductions = Math.round(dedTotal * 100) / 100;
            this.totals.ytd_deductions = Math.round(ytdDed * 100) / 100;

            this.totals.net_pay = Math.max(0, Math.round((gross - dedTotal) * 100) / 100);
            this.totals.ytd_net_pay = Math.max(0, Math.round((ytdGross - ytdDed) * 100) / 100);
        },

        formatCurrency(num) {
            const val = parseFloat(num) || 0;
            return (this.currency.symbol || '$') + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        downloadPdf() {
            const form = document.getElementById('paystubExportForm');
            
            // Post payload directly
            const payload = {
                company: this.company,
                employee: this.employee,
                pay_period: this.pay_period,
                currency: this.currency,
                earnings: this.earnings,
                deductions: this.deductions,
                totals: this.totals,
                payment: this.payment,
            };

            // Set as fields in form
            form.innerHTML = `@csrf`;
            const appendField = (name, val) => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = name;
                inp.value = val;
                form.appendChild(inp);
            };

            appendField('company[name]', this.company.name);
            appendField('company[address]', this.company.address);
            appendField('company[ein]', this.company.ein);

            appendField('employee[name]', this.employee.name);
            appendField('employee[title]', this.employee.title);
            appendField('employee[id]', this.employee.id);
            appendField('employee[ssn_last4]', this.employee.ssn_last4);
            appendField('employee[address]', this.employee.address);

            appendField('pay_period[frequency]', this.pay_period.frequency);
            appendField('pay_period[start_date]', this.pay_period.start_date);
            appendField('pay_period[end_date]', this.pay_period.end_date);
            appendField('pay_period[pay_date]', this.pay_period.pay_date);

            appendField('currency[symbol]', this.currency.symbol);

            this.earnings.forEach((e, i) => {
                appendField(`earnings[${i}][description]`, e.description);
                appendField(`earnings[${i}][hours]`, e.hours);
                appendField(`earnings[${i}][rate]`, e.rate);
                appendField(`earnings[${i}][amount]`, e.amount);
                appendField(`earnings[${i}][ytd]`, e.ytd);
            });

            this.deductions.forEach((d, i) => {
                appendField(`deductions[${i}][description]`, d.description);
                appendField(`deductions[${i}][amount]`, d.amount);
                appendField(`deductions[${i}][ytd]`, d.ytd);
            });

            form.submit();
        },

        resetToDefaults() {
            Object.assign(this, JSON.parse(JSON.stringify(defaultData)));
            this.recalculateTotals();
        }
    };
}
</script>
@endsection
