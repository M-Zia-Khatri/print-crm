<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ExportReport implements ShouldQueue
{
    use Queueable;

    /** @param array<string, mixed> $filters */
    public function __construct(public readonly string $report, public readonly array $filters = []) {}

    public function handle(): void
    {
        Log::info('Report export queued.', ['report' => $this->report, 'filters' => $this->filters]);
    }
}
