<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;

class MarkOverdueInvoices
{
    public function __invoke(): int
    {
        return Invoice::query()
            ->whereNotIn('status', ['paid', 'void', 'overdue'])
            ->whereDate('due_at', '<', today())
            ->update(['status' => 'overdue']);
    }
}
