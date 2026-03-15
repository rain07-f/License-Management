<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Plan;
use App\Models\Application;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('application')->latest()->paginate(10);
        $applications = Application::all();
        return view('admin.plans.index', compact('plans', 'applications'));
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
            'application_id' => 'nullable|exists:applications,id',
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
            'application_id' => 'nullable|exists:applications,id',
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

    /**
     * AJAX: Get plans filtered by application.
     */
    public function byApplication(Application $application)
    {
        return response()->json(
            $application->plans()->where('active', true)->get(['id', 'name'])
        );
    }

    /**
     * AJAX: Get general plans (no application).
     */
    public function general()
    {
        return response()->json(
            Plan::whereNull('application_id')->where('active', true)->get(['id', 'name'])
        );
    }
}
