<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('invoice_number', 50)->default('INV-0001');
            $table->string('invoice_title', 100)->default('INVOICE');
            $table->string('status', 30)->default('draft');
            $table->string('currency', 10)->default('USD');
            $table->string('currency_symbol', 10)->default('$');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('payment_terms', 100)->nullable();
            $table->string('po_number', 50)->nullable();
            
            // Sender details
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_phone', 50)->nullable();
            $table->text('from_address')->nullable();
            $table->string('from_tax_id', 50)->nullable();

            // Client details
            $table->string('to_name')->nullable();
            $table->string('to_email')->nullable();
            $table->string('to_phone', 50)->nullable();
            $table->text('to_address')->nullable();
            $table->string('to_tax_id', 50)->nullable();

            // Financial breakdown
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->string('discount_type', 20)->default('percent');
            $table->decimal('discount_rate', 10, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_rate', 10, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('shipping_fee', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('balance_due', 14, 2)->default(0);

            // Instructions, branding and options
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->longText('logo_data')->nullable();
            $table->string('accent_color', 30)->default('#0d9488');
            $table->string('template_style', 30)->default('modern');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
