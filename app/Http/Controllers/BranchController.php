<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // List all branches with search and service filtering
    public function index(Request $request)
    {
        $query = Branch::with('service');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $branches = $query->orderBy('created_at', 'desc')->paginate(5);
        $services = Service::all();

        return view('branches.index', compact('branches', 'services'));
    }

    // Show form to create new branch
    public function create()
    {
        $services = Service::all();
        return view('branches.create', compact('services'));
    }

    // Save new branch to database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string',
            'contact_number' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|string',
        ]);
        Branch::create($request->all());
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    // Display single branch details
    public function show(Branch $branch)
    {
        $branch->load('service');
        return view('branches.show', compact('branch'));
    }

    // Show form to edit branch
    public function edit(Branch $branch)
    {
        $services = Service::all();
        return view('branches.edit', compact('branch', 'services'));
    }

    // Update branch in database
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string',
            'contact_number' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|string',
        ]);
        $branch->update($request->all());
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }
    
    // Delete branch from database
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
