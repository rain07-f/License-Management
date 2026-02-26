<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\License;
use App\Models\User;
use App\Models\Plan;
use App\Services\LicenseService;
use Exception;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = License::with(['owner', 'plan', 'generator', 'activations']);

        if (!$user->isSuperAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                    ->orWhere('generated_by', $user->id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('license_key_hash', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $licenses = $query->latest()->paginate(10)->withQueryString();
        $plans = Plan::all();
        $clients = [];
        if (auth()->user()->isSuperAdmin()) {
            $clients = User::where('role', 'client')->get();
        } else {
            $clients = User::where('parent_id', auth()->id())->where('role', 'client')->get();
        }
        return view('admin.licenses.index', compact('licenses', 'plans', 'clients'));
    }

    public function export()
    {
        $user = auth()->user();
        $query = License::with(['owner', 'plan', 'activations']);

        if (!$user->isSuperAdmin()) {
            $query->where('generated_by', $user->id);
        }

        $licenses = $query->get();
        $filename = "licenses_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($licenses) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Owner', 'Plan', 'Key Hash', 'Status', 'Expires At', 'Activations Used', 'Created At']);

            foreach ($licenses as $l) {
                fputcsv($file, [
                    $l->id,
                    $l->owner->name,
                    $l->plan->name,
                    $l->license_key_hash,
                    $l->status,
                    $l->expires_at ? $l->expires_at->format('Y-m-d') : 'Never',
                    $l->activations->count(),
                    $l->created_at->format('Y-m-d')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $plans = Plan::all();
        $clients = [];
        if (auth()->user()->isSuperAdmin()) {
            $clients = User::where('role', 'client')->get();
        } else {
            $clients = User::where('parent_id', auth()->id())->where('role', 'client')->get();
        }
        return view('admin.licenses.create', compact('plans', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'owner_id' => 'nullable|exists:users,id',
        ]);

        $generator = auth()->user();
        $owner = $request->owner_id ? User::find($request->owner_id) : $generator;
        $plan = Plan::find($request->plan_id);

        try {
            $license = $this->licenseService->generate($generator, $owner, $plan);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'License generated successfully.',
                    'data' => $license->load(['owner', 'plan', 'generator', 'activations']),
                    'display_key' => $license->license_key_display
                ]);
            }

            return redirect()->route('admin.licenses.index')
                ->with('success', 'License generated successfully. KEY: ' . $license->license_key_display);
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function assign(Request $request, License $license)
    {
        $request->validate(['client_id' => 'required|exists:users,id']);

        $license->update(['owner_id' => $request->client_id]);
        $this->licenseService->logAction($license, auth()->user(), 'transfer');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'License assigned to client successfully.',
                'data' => $license->fresh()->load(['owner', 'plan', 'generator', 'activations'])
            ]);
        }

        return back()->with('success', 'License assigned to client successfully.');
    }

    public function show(License $license)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->isSuperAdmin() && $license->owner_id !== $user->id && $license->generated_by !== $user->id) {
            abort(403);
        }

        $license->load(['owner', 'plan', 'generator', 'activations', 'logs.user']);
        $plans = Plan::all();
        return view('admin.licenses.show', compact('license', 'plans'));
    }

    public function revoke(License $license)
    {
        $license->update(['status' => 'revoked']);
        $this->licenseService->logAction($license, auth()->user(), 'revoke');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'License revoked.',
                'data' => $license->fresh()->load(['owner', 'plan', 'generator', 'activations'])
            ]);
        }

        return back()->with('success', 'License revoked.');
    }

    public function renew(Request $request, License $license)
    {
        $request->validate(['plan_id' => 'required|exists:plans,id']);
        $plan = Plan::find($request->plan_id);

        try {
            $this->licenseService->renew($license, auth()->user(), $plan);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'License renewed successfully.',
                    'data' => $license->fresh()->load(['owner', 'plan', 'generator', 'activations'])
                ]);
            }

            return back()->with('success', 'License renewed successfully.');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(License $license)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $license->owner_id !== $user->id && $license->generated_by !== $user->id) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403);
        }

        $this->licenseService->logAction($license, $user, 'revoke'); // Log before deletion
        $license->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'License deleted permanently.']);
        }

        return redirect()->route('admin.licenses.index')->with('success', 'License deleted permanently.');
    }

    public function revokeActivation(Request $request, \App\Models\LicenseActivation $activation)
    {
        $license = $activation->license;
        $user = auth()->user();

        if (!$user->isSuperAdmin() && $license->owner_id !== $user->id && $license->generated_by !== $user->id) {
            abort(403);
        }

        $activation->update([
            'status' => 'revoked',
            'revoked_at' => now()
        ]);

        $this->licenseService->logAction($license, $user, 'revoke_domain', $activation->domain);

        return back()->with('success', 'Activation revoked successfully.');
    }
}
