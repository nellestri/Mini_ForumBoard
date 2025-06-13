<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;
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

        // Enhanced user statistics
        $topicsCount = $user->topics()->count();
        $repliesCount = $user->replies()->count();
        $totalViews = $user->topics()->sum('views');

        // Additional stats
        $popularTopics = $user->topics()->where('views', '>', 10)->count();
        $recentActivity = $user->topics()->where('created_at', '>=', now()->subDays(7))->count() +
                         $user->replies()->where('created_at', '>=', now()->subDays(7))->count();

        $stats = [
            'topics_count' => $topicsCount,
            'replies_count' => $repliesCount,
            'total_views' => $totalViews,
            'popular_topics' => $popularTopics,
            'recent_activity' => $recentActivity,
            'avg_views_per_topic' => $topicsCount > 0 ? round($totalViews / $topicsCount, 1) : 0,
        ];

        // Recent topics with enhanced data
        $recent_topics = $user->topics()
            ->withCount('replies')
            ->with(['user', 'latestReply'])
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
            ->with('latestReply.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.topics', compact('topics'));
    }

    public function replies(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $replies = $user->replies()
            ->with(['topic' => function($query) {
                $query->select('id', 'title', 'user_id');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.replies', compact('replies'));
    }
}
