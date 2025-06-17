<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // User statistics
        $stats = [
            'topics_count' => $user->topics()->count(),
            'replies_count' => $user->replies()->count(),
            'total_views' => $user->topics()->sum('views'),
            'pinned_topics' => $user->topics()->where('is_pinned', true)->count(),
        ];

        // Recent topics with replies count and latest reply
        $recent_topics = $user->topics()
            ->withCount('replies')
            ->with(['user', 'latestReply.user'])
            ->latest()
            ->take(5)
            ->get();

        // Recent replies with topic context
        $recent_replies = $user->replies()
            ->with(['topic' => function($query) {
                $query->select('id', 'title', 'user_id');
            }])
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recent_topics', 'recent_replies'));
    }

    public function topics(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $topics = $user->topics()
            ->withCount('replies')
            ->latest()
            ->paginate(10);

        return view('user.topics', compact('topics'));
    }

    public function replies(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $replies = $user->replies()
            ->with('topic')
            ->latest()
            ->paginate(10);

        return view('user.replies', compact('replies'));
    }

    // Show any user's profile (public view)
    public function showProfile(User $user): View
    {
        $stats = [
            'topics_count' => $user->topics()->count(),
            'replies_count' => $user->replies()->count(),
            'total_views' => $user->topics()->sum('views'),
            'member_since' => $user->created_at,
            'last_active' => $user->last_active,
        ];

        // Recent topics by this user
        $recent_topics = $user->topics()
            ->withCount('replies')
            ->latest()
            ->take(5)
            ->get();

        // Recent replies by this user
        $recent_replies = $user->replies()
            ->with('topic')
            ->latest()
            ->take(5)
            ->get();

        return view('user.profile', compact('user', 'stats', 'recent_topics', 'recent_replies'));
    }

    // Show current user's own profile
    public function profile(): View
    {
        return $this->showProfile(Auth::user());
    }

    // Edit current user's profile
    public function editProfile(): View
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'bio', 'location', 'website']);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return redirect()->route('user.profile', $user)->with('success', 'Profile updated successfully!');
    }
}
