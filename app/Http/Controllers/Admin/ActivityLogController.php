<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  


class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('activity_logs')->orderBy('created_at', 'desc');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        $logs = $query->paginate(50);

        return view('admin.activity-logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = DB::table('activity_logs')->where('id', $id)->first();

        if (!$log) {
            abort(404);
        }

        return view('admin.activity-logs.show', compact('log'));
    }

    public function clear()
    {
        DB::table('activity_logs')->truncate();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
    // public function index()
    // {
    //     // Implement activity log listing
    //     return view('admin.activity-logs.index');
    // }

    // public function show($id)
    // {
    //     // Implement single activity log view
    //     return view('admin.activity-logs.show');
    // }
}
