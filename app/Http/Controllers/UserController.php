<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Professional English: Displays the edit form for a specific user.
     * @param int $id The user_id from the database.
     */
    public function edit($id)
    {
        // Using user_id because we changed the primary key in migrations
        $user = User::where('user_id', $id)->firstOrFail();
        
        return view('users.edit', compact('user'));
    }

    /**
     * Professional English: Updates the user record in the database.
     */
    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'role' => 'required|in:general_public,snake_enthusiast,admin',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
}