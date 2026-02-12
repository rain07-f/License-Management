<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\QuotaLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('parent');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $distributors = User::where('role', 'distributor')->get();
        return view('admin.users.create', compact('distributors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:distributor,client',
            'parent_id' => 'nullable|exists:users,id',
            'license_quota' => 'nullable|integer|min:0',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'parent_id' => $request->parent_id,
            'license_quota' => $request->license_quota ?? 0,
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $distributors = User::where('role', 'distributor')->where('id', '!=', $user->id)->get();
        return view('admin.users.edit', compact('user', 'distributors'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,suspended',
        ]);

        $data = $request->only(['name', 'email', 'status']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function addQuota(Request $request, User $user)
    {
        $request->validate(['quota' => 'required|integer|min:1']);

        DB::transaction(function () use ($user, $request) {
            $prev = $user->license_quota;
            $user->increment('license_quota', $request->quota);

            QuotaLog::create([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'amount' => $request->quota,
                'previous_quota' => $prev,
                'current_quota' => $user->fresh()->license_quota,
                'note' => 'Manual adjustment by admin',
            ]);
        });

        return back()->with('success', "Added {$request->quota} licenses to quota.");
    }

    public function quotaHistory()
    {
        $user = auth()->user();
        $query = QuotaLog::with(['user', 'admin']);

        if (!$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        $logs = $query->latest()->paginate(15);
        return view('admin.users.quota_history', compact('logs'));
    }
}
