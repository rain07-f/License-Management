<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Domain;
use App\Models\DomainActivityLog;
use App\Services\DomainService;
use App\Services\LicenseService;

class DomainController extends Controller
{
    protected $domainService;
    protected $licenseService;

    public function __construct(DomainService $domainService, LicenseService $licenseService)
    {
        $this->domainService = $domainService;
        $this->licenseService = $licenseService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Domain::with(['license.owner', 'activator']);

        if (!$user->isSuperAdmin()) {
            $query->whereHas('license', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('domain_name', 'like', "%{$search}%")
                    ->orWhereHas('license', function ($sq) use ($search) {
                        $sq->where('license_key_hash', 'like', "%{$search}%");
                    });
            });
        }

        $domains = $query->latest()->paginate(10)->withQueryString();
        return view('admin.domains.index', compact('domains'));
    }

    public function show($id)
    {
        $domain = Domain::with(['license.owner', 'license.plan'])->findOrFail($id);
        $logs = DomainActivityLog::where('domain_id', $domain->id)
            ->latest()
            ->take(10)
            ->get();
            
        return view('admin.domains.show', compact('domain', 'logs'));
    }

    public function activity($id)
    {
        $domain = Domain::findOrFail($id);
        $logs = DomainActivityLog::where('domain_id', $domain->id)
            ->latest()
            ->paginate(50);

        return view('admin.domains.activity', compact('domain', 'logs'));
    }

    public function deactivate($id)
    {
        $domain = Domain::findOrFail($id);

        $domain->status = 'inactive';
        $domain->save();

        DomainActivityLog::create([
            'domain_id' => $domain->id,
            'event_type' => 'domain_deactivated',
            'message' => 'Domain deactivated by admin',
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Domain deactivated.');
    }

    public function reactivate($id)
    {
        $domain = Domain::findOrFail($id);

        $domain->status = 'active';
        $domain->save();

        DomainActivityLog::create([
            'domain_id' => $domain->id,
            'event_type' => 'domain_reactivated',
            'message' => 'Domain reactivated by admin',
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Domain reactivated.');
    }

    public function destroy(Domain $domain)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->isSuperAdmin() && $domain->license->owner_id !== $user->id) {
            abort(403);
        }

        $license = $domain->license;
        $domainName = $domain->domain_name;

        // Instead of deleting, we now update status to inactive
        $domain->status = 'inactive';
        $domain->save();

        // Log using the old method (LicenseService) for backward compatibility if needed
        $this->licenseService->logAction($license, $user, 'deactivate', $domainName, request()->ip());

        // Log using the new DomainActivityLog
        DomainActivityLog::create([
            'domain_id' => $domain->id,
            'event_type' => 'domain_deactivated',
            'message' => 'Domain deactivated by admin (legacy action)',
            'ip_address' => request()->ip()
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Domain {$domainName} deactivated successfully."
            ]);
        }

        return back()->with('success', "Domain {$domainName} deactivated successfully.");
    }
}
