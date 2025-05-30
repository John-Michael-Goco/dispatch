<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // List all users except admin with search functionality
    public function index(Request $request)
    {
        $query = User::with('userInfo')
            ->where(function ($query) {
                $query->where('name', '!=', 'admin')
                    ->orWhere('email', '!=', 'admin@gmail.com');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('userInfo', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    });
            });
        }

        $users = $query->paginate(10);
        return view('users.index', compact('users'));
    }


    // Show form to create new user
    public function create()
    {
        return view('users.create');
    }


    // Save new user and their info to database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->userInfo()->create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }


    // Display single user details
    public function show(User $user)
    {
        if ($user->name === 'admin' && $user->email === 'admin@gmail.com') {
            return redirect()->route('users.index')
                ->with('error', 'Access denied.');
        }

        $user->load('userInfo');
        return view('users.show', compact('user'));
    }


    // Show form to edit user
    public function edit(User $user)
    {
        if ($user->name === 'admin' && $user->email === 'admin@gmail.com') {
            return redirect()->route('users.index')
                ->with('error', 'Access denied.');
        }

        $user->load('userInfo');
        return view('users.edit', compact('user'));
    }


    // Update user and their info in database
    public function update(Request $request, User $user)
    {
        if ($user->name === 'admin' && $user->email === 'admin@gmail.com') {
            return redirect()->route('users.index')
                ->with('error', 'Access denied.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->userInfo()->update([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }


    // Delete user from database
    public function destroy(User $user)
    {
        if ($user->name === 'admin' && $user->email === 'admin@gmail.com') {
            return redirect()->route('users.index')
                ->with('error', 'Access denied.');
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
