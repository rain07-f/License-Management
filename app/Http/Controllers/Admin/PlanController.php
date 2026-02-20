<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_days' => 'required|integer|min:1',
            'domain_limit' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $plan = Plan::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Plan created successfully.',
                'data' => $plan
            ]);
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_days' => 'required|integer|min:1',
            'domain_limit' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $plan->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Plan updated successfully.',
                'data' => $plan
            ]);
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Plan deleted successfully.'
            ]);
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
