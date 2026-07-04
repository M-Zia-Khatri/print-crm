<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateInvoicePdf implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $invoiceId) {}

    public function handle(): void
    {
        $invoice = Invoice::query()->findOrFail($this->invoiceId);

        Log::info('Invoice PDF generation queued.', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->number,
        ]);
    }
}
