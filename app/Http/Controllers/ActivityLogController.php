<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->input('role');
        $search = $request->input('q');

        $query = ActivityLog::query();

        if ($role) {
            $query->where('user_role', $role);
        }

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->where('aksi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('logs.index', compact('logs', 'role', 'search'));
    }
}
