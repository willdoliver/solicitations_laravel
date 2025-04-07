<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * Log an action.
     *
     * @param string $action
     * @param string $description
     * @param int|null $solicitationId
     * @return void
     */
    public static function log(
        string $action,
        string $description,
        int|null $solicitationId = null
    ): void {
        Log::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'solicitation_id' => $solicitationId,
        ]);
    }
}