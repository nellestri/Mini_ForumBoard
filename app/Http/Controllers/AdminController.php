<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_topics' => Topic::count(),
            'total_replies' => Reply::count(),
            'active_users' => User::where('last_active', '>=', now()->subDays(7))->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_topics = Topic::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_topics'));
    }

    public function users()
    {
        $users = User::withCount(['topics', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function toggleUserRole(User $user)
    {
        $user->update([
            'role' => $user->role === 'admin' ? 'user' : 'admin'
        ]);

        return back()->with('success', 'User role updated successfully!');
    }

    public function toggleTopicPin(Topic $topic)
    {
        $topic->update(['is_pinned' => !$topic->is_pinned]);
        return back()->with('success', 'Topic pin status updated!');
    }

    public function toggleTopicLock(Topic $topic)
    {
        $topic->update(['is_locked' => !$topic->is_locked]);
        return back()->with('success', 'Topic lock status updated!');
    }
}
