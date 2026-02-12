<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\License;
use App\Models\Domain;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->isSuperAdmin()) {
            $stats = [
                'total_users' => User::count(),
                'total_distributors' => User::where('role', 'distributor')->count(),
                'total_licenses' => License::count(),
                'active_licenses' => License::where('status', 'active')->count(),
                'expired_licenses' => License::where('status', 'expired')->count(),
                'active_domains' => Domain::where('status', 'active')->count(),
                'monthly_stats' => $this->getMonthlyStats(),
            ];
        } else if ($user->isDistributor()) {
            $stats = [
                'total_licenses' => License::where('owner_id', $user->id)->count(),
                'remaining_quota' => $user->license_quota,
                'active_licenses' => License::where('owner_id', $user->id)->where('status', 'active')->count(),
                'expired_licenses' => License::where('owner_id', $user->id)->where('status', 'expired')->count(),
            ];
        } else {
            $stats = [
                'total_licenses' => License::where('owner_id', $user->id)->count(),
                'active_domains' => Domain::where('activated_by', $user->id)->count(),
            ];
        }

        return view('admin.dashboard', compact('stats'));
    }

    private function getMonthlyStats()
    {
        return License::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('MONTH(created_at) as month_num')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();
    }
}
