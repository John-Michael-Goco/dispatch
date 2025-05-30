<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    // List all services with search functionality
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('contact_line', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate(10)->withQueryString();
        return view('services.index', compact('services'));
    }

    // Show form to create new service
    public function create()
    {
        return view('services.create');
    }

    // Save new service to database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_line' => 'nullable|string|max:255',
        ]);
        Service::create($request->all());
        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    // Display single service details with its branches
    public function show(Service $service)
    {
        $branches = $service->branches()->get();
        return view('services.show', compact('service', 'branches'));
    }

    // Show form to edit service
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    // Update service in database
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_line' => 'nullable|string|max:255',
        ]);
        $service->update($request->all());
        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    // Delete service from database
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
