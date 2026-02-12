<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LicenseLog;

class LogController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = LicenseLog::with(['license', 'user']);

        if (!$user->isSuperAdmin()) {
            $query->whereHas('license', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        }

        $logs = $query->latest()->paginate(20);
        return view('admin.logs.index', compact('logs'));
    }
}
