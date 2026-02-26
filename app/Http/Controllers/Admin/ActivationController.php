<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LicenseActivation;
use App\Services\LicenseService;
use Exception;

class ActivationController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Display a listing of all active/revoked activations.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = LicenseActivation::with(['license.owner', 'license.plan']);

        // Filter by user if not super admin
        if (!$user->isSuperAdmin()) {
            $query->whereHas('license', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('domain', 'like', "%{$search}%")
                    ->orWhere('device_uid', 'like', "%{$search}%")
                    ->orWhereHas('license', function ($sq) use ($search) {
                        $sq->where('license_key_hash', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.activations.index', compact('activations'));
    }

    /**
     * View activation details.
     */
    public function show(LicenseActivation $activation)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->isSuperAdmin() && $activation->license->owner_id !== $user->id) {
            abort(403);
        }

        $activation->load([
            'license.owner',
            'license.plan',
            'license.logs' => function ($q) use ($activation) {
                $q->where('domain', $activation->domain)->latest();
            }
        ]);

        return view('admin.activations.show', compact('activation'));
    }

    /**
     * Revoke a specific activation.
     */
    public function destroy(LicenseActivation $activation)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->isSuperAdmin() && $activation->license->owner_id !== $user->id) {
            abort(403);
        }

        if ($activation->status === 'revoked') {
            return response()->json([
                'success' => false,
                'message' => 'Activation is already revoked.'
            ], 400);
        }

        $activation->update([
            'status' => 'revoked',
            'revoked_at' => now(),
        ]);

        // Log action
        $this->licenseService->logAction($activation->license, $user, 'revoke_pair', $activation->domain, request()->ip());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Activation revoked successfully.'
            ]);
        }

        return back()->with('success', 'Activation revoked successfully.');
    }
}
