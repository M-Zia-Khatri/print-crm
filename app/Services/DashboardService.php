<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /** @return array<string, mixed> */
    public function metrics(): array
    {
        return Cache::remember('dashboard.metrics', now()->addMinutes(5), fn (): array => [
            'customers' => Customer::query()->count(),
            'companies' => Company::query()->count(),
            'products' => Product::query()->where('is_active', true)->count(),
            'open_tasks' => Task::query()->whereNull('completed_at')->count(),
            'invoice_total' => (float) Invoice::query()->sum('total'),
            'payments_total' => (float) Payment::query()->sum('amount'),
            'overdue_invoices' => Invoice::query()
                ->whereNotIn('status', ['paid', 'void'])
                ->whereDate('due_at', '<', today())
                ->count(),
        ]);
    }
}
