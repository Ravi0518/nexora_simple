<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Professional English Documentation
 * Class: UserController
 * Purpose: Provides Administrative CRUD operations for the User entity.
 */
class UserController extends Controller
{
    // Display the list of users
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show the edit form
    public function edit($id)
    {
        // Finding by SDS custom primary key: user_id
        $user = User::where('user_id', $id)->firstOrFail();
        return view('admin.users.edit', compact('user'));
    }

    // Update the user record
    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'role' => 'required|in:admin,general_public,snake_enthusiast',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'User profile synchronized successfully.');
    }

    // Delete a user
    public function destroy($id)
    {
        // Security Check: Prevent self-deletion
        if (Auth::user()->user_id == $id) {
            return back()->with('error', 'Critical Error: You cannot delete your own administrative account.');
        }

        User::where('user_id', $id)->delete();
        return back()->with('success', 'User removed from Nexora system.');
    }
}