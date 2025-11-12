<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // فاتورة تجريبية 1
        $invoice1 = Invoice::create([
            'invoice_number' => 'INV-2025-001',
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'customer_name' => 'شركة الرياض للتجارة',
            'customer_email' => 'info@riyadh-trading.com',
            'customer_address' => 'الرياض، المملكة العربية السعودية',
            'subtotal' => 10000.00,
            'tax_amount' => 1500.00,
            'discount_amount' => 0.00,
            'total' => 11500.00,
            'currency_code' => 'SAR',
            'status' => 'sent',
            'notes' => 'شكراً لتعاملكم معنا',
            'terms' => 'الدفع خلال 30 يوم من تاريخ الفاتورة',
            'synced_to_zoho' => false,
        ]);

        // عناصر الفاتورة 1
        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'item_name' => 'خدمات استشارية',
            'description' => 'استشارات تقنية لمدة شهر',
            'quantity' => 1,
            'rate' => 10000.00,
            'amount' => 10000.00,
            'tax_percentage' => 15.00,
            'tax_amount' => 1500.00,
        ]);

        // فاتورة تجريبية 2
        $invoice2 = Invoice::create([
            'invoice_number' => 'INV-2025-002',
            'invoice_date' => now()->subDays(5),
            'due_date' => now()->addDays(25),
            'customer_name' => 'مؤسسة جدة للتطوير',
            'customer_email' => 'contact@jeddah-dev.com',
            'customer_address' => 'جدة، المملكة العربية السعودية',
            'subtotal' => 25000.00,
            'tax_amount' => 3750.00,
            'discount_amount' => 500.00,
            'total' => 28250.00,
            'currency_code' => 'SAR',
            'status' => 'draft',
            'notes' => 'فاتورة مسودة',
            'synced_to_zoho' => false,
        ]);

        // عناصر الفاتورة 2
        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'item_name' => 'تطوير موقع إلكتروني',
            'description' => 'تصميم وتطوير موقع متكامل',
            'quantity' => 1,
            'rate' => 20000.00,
            'amount' => 20000.00,
            'tax_percentage' => 15.00,
            'tax_amount' => 3000.00,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'item_name' => 'استضافة سنوية',
            'description' => 'استضافة وصيانة لمدة سنة',
            'quantity' => 1,
            'rate' => 5000.00,
            'amount' => 5000.00,
            'tax_percentage' => 15.00,
            'tax_amount' => 750.00,
            'discount_amount' => 500.00,
        ]);

        // فاتورة تجريبية 3 - مدفوعة
        $invoice3 = Invoice::create([
            'invoice_number' => 'INV-2025-003',
            'invoice_date' => now()->subDays(45),
            'due_date' => now()->subDays(15),
            'customer_name' => 'شركة الدمام للصناعات',
            'customer_email' => 'sales@dammam-industries.com',
            'customer_address' => 'الدمام، المملكة العربية السعودية',
            'subtotal' => 15000.00,
            'tax_amount' => 2250.00,
            'discount_amount' => 0.00,
            'total' => 17250.00,
            'currency_code' => 'SAR',
            'status' => 'paid',
            'notes' => 'تم الدفع بالكامل',
            'synced_to_zoho' => false,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice3->id,
            'item_name' => 'نظام إدارة مخزون',
            'description' => 'تطوير نظام إدارة مخزون متكامل',
            'quantity' => 1,
            'rate' => 15000.00,
            'amount' => 15000.00,
            'tax_percentage' => 15.00,
            'tax_amount' => 2250.00,
        ]);

        $this->command->info('تم إنشاء 3 فواتير تجريبية بنجاح!');
    }
}
