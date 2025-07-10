<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): Response
    {
        $users = User::latest()->get();
        
        $stats = [
            'total' => User::count(),
            'approved' => User::where('status', 'approved')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
        ];
        
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }

    /**
     * Approve a user.
     */
    public function approve(User $user)
    {
        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', "User {$user->name} has been approved.");
    }

    /**
     * Reject a user.
     */
    public function reject(User $user)
    {
        $user->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', "User {$user->name} has been rejected.");
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', "User {$user->name} has been deleted.");
    }
}
