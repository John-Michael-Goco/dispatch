<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Service;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    // List all incidents with search and filtering
    public function index(Request $request)
    {
        $query = Incident::with(['service', 'branch', 'reporter']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branch', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Default sort by status priority and then by created_at
        $query->orderByRaw("
            CASE 
                WHEN status = 'open' THEN 1
                WHEN status = 'in_progress' THEN 2
                WHEN status = 'resolved' THEN 3
                WHEN status = 'closed' THEN 4
            END
        ")->orderBy('created_at', 'desc');

        $incidents = $query->paginate(10);
        return view('incidents.index', compact('incidents'));
    }

    // Show form to create new incident
    public function create()
    {
        $services = Service::all();
        $branches = Branch::all();
        return view('incidents.create', compact('services', 'branches'));
    }

    // Save new incident to database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'latitude' => 'required|string|max:20',
            'longitude' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $validated['reported_by'] = Auth::id();
        $validated['status'] = 'open'; // Set default status

        Incident::create($validated);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident created successfully.');
    }

    // Display single incident details
    public function show(Incident $incident)
    {
        $incident->load(['reporter', 'service', 'branch']);
        return view('incidents.show', compact('incident'));
    }

    // Show form to edit incident
    public function edit(Incident $incident)
    {
        $services = Service::all();
        $branches = Branch::all();
        return view('incidents.edit', compact('incident', 'services', 'branches'));
    }

    // Update incident in database
    public function update(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'branch_id' => 'required|exists:branches,id',
            'description' => 'required|string',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $incident->update($validated);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident updated successfully.');
    }

    // Delete incident from database
    public function destroy(Incident $incident)
    {
        $incident->delete();

        return redirect()->route('incidents.index')
            ->with('success', 'Incident deleted successfully.');
    }
}
