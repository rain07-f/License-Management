<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Domain;
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

    public function destroy(Domain $domain)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->isSuperAdmin() && $domain->license->owner_id !== $user->id) {
            abort(403);
        }

        $license = $domain->license;
        $domainName = $domain->domain_name;

        $domain->delete();

        // Log the manual deactivation
        $this->licenseService->logAction($license, $user, 'deactivate', $domainName, request()->ip());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Domain {$domainName} deactivated successfully."
            ]);
        }

        return back()->with('success', "Domain {$domainName} deactivated successfully.");
    }
}
