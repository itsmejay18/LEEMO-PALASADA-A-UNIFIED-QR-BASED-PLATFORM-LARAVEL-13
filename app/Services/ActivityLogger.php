<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ActivityLogger
{
    /**
     * @param  array<string, mixed>|string|null  $details
     */
    public function log(string $action, array|string|null $details = null, ?User $user = null, ?string $ipAddress = null): void
    {
        try {
            if (! Schema::hasTable('activity_logs')) {
                return;
            }

            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'details' => is_array($details) ? json_encode($details, JSON_UNESCAPED_SLASHES) : $details,
                'ip_address' => $ipAddress,
                'created_at' => now(),
            ]);
        } catch (Throwable) {
            // Activity logging should never block the main workflow.
        }
    }
}
