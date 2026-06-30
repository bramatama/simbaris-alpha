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
    public function index(Request $request)
    {
        $query = User::query()
            ->with(['officialTeam', 'committee', 'judge'])
            ->where('user_id', '!=', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return inertia('admin/UserManagement/Index', [
            'users' => $users,
            'filters' => $request->only(['role']),
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

        // if ($user->role !== 'official_team') {
        // return redirect()->back()
        //     ->with('error', 'Only users with the official_team role can be deleted.');
        // }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('message', 'User deleted successfully.');
    }
}
