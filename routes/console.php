<?php

use App\Actions\Invoices\MarkOverdueInvoices;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('crm:mark-overdue-invoices', function () {
    $updated = app(MarkOverdueInvoices::class)();
    $this->info("Marked {$updated} invoices as overdue.");
})->purpose('Mark unpaid invoices as overdue after their due date');

Schedule::command('crm:mark-overdue-invoices')->dailyAt('01:00')->name('invoices.mark-overdue')->withoutOverlapping();
Schedule::command('queue:prune-failed --hours=168')->dailyAt('02:00')->name('queues.prune-failed');
