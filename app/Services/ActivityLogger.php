<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    /** @param array<string, mixed> $properties */
    public function log(string $event, ?Model $subject = null, array $properties = [], ?Request $request = null): ActivityLog
    {
        return ActivityLog::query()->create([
            'user_id' => $request?->user()?->id,
            'event' => $event,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => $request?->ip(),
        ]);
    }
}
