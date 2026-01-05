<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public static function log($action, $description, $userId)
    {
        Log::info("User ID: {$userId} performed action: {$action} with description: {$description}");

        Activity::create([
            'user_id' => $userId,
            'action' => strtolower($action),
            'description' => $description
        ]);
    }

    public static function getNewestActivities() {
        return Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}
