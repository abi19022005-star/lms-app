<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

function logActivity($data = [])
{
    \App\Models\ActivityLog::create([
        'user_id' => Auth::id(),
        'user_name' => Auth::user()->name ?? null,
        'user_email' => Auth::user()->email ?? null,
        'user_role' => Auth::user()->role ?? null,

        'action' => $data['action'] ?? null,
        'action_type' => $data['action_type'] ?? null,
        'module' => $data['module'] ?? null,

        'model_type' => $data['model_type'] ?? null,
        'model_id' => $data['model_id'] ?? null,
        'model_name' => $data['model_name'] ?? null,

        'old_data' => $data['old_data'] ?? null,
        'new_data' => $data['new_data'] ?? null,
        'description' => $data['description'] ?? null,

        'method' => request()->method(),
        'url' => request()->fullUrl(),
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'device_type' => str_contains(request()->userAgent(), 'Mobile') ? 'mobile' : 'desktop',

        'metadata' => json_encode($data['metadata'] ?? []),
        'is_error' => $data['is_error'] ?? false,
        'error_message' => $data['error_message'] ?? null,
    ]);
}
