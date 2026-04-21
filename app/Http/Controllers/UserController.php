<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of all users (paginated)
     */
    public function index()
    {
        $users = User::query()
            ->select(['user_id', 'public_id', 'name', 'email', 'role', 'contact_info', 'created_at'])
            ->orderByDesc('created_at')
            ->where('user_id', '!=', auth()->id())
            ->paginate(15);

        return inertia('admin/UserManagement/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Display the specified user for editing
     */
    public function show(User $user)
    {
        return inertia('admin/UserManagement/Edit', [
            'user' => $user->only(['user_id', 'public_id', 'name', 'email', 'role', 'contact_info']),
        ]);
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        $currentUserId = auth()->user()?->user_id;
        if ($user->user_id === $currentUserId) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->role !== 'official_team') {
        return redirect()->back()
            ->with('error', 'Only users with the official_team role can be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('message', 'User deleted successfully.');
    }
}
