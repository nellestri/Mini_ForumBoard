<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $topics = Topic::with(['user', 'latestReply.user'])
            ->withCount('replies')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('forum.index', compact('topics'));
    }
}

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Topic $topic)
    {
        if ($topic->is_locked && !Auth::user()->role === 'admin') {
            return back()->with('error', 'This topic is locked.');
        }

        $request->validate([
            'content' => 'required|string|min:5',
        ]);

        Reply::create([
            'content' => $request->content,
            'topic_id' => $topic->id,
            'user_id' => Auth::id(),
        ]);

        $topic->touch(); // Update topic's updated_at timestamp

        return back()->with('success', 'Reply posted successfully!');
    }

    public function edit(Reply $reply)
    {
        if (Gate::denies('update', $reply)) {
            abort(403);
        }

        return view('forum.edit-reply', compact('reply'));
    }

    public function update(Request $request, Reply $reply)
    {
        if (Gate::denies('update', $reply)) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|min:5',
        ]);

        $reply->update([
            'content' => $request->content,
        ]);

        return redirect()->route('topic.show', $reply->topic)->with('success', 'Reply updated successfully!');
    }

    public function destroy(Reply $reply)
    {
        if (Gate::denies('delete', $reply)) {
            abort(403);
        }

        $topic = $reply->topic;
        $reply->delete();

        return redirect()->route('topic.show', $topic)->with('success', 'Reply deleted successfully!');
    }
}
namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    public function show(Topic $topic)
    {
        $topic->incrementViews();

        $replies = $topic->replies()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('forum.topic', compact('topic', 'replies'));
    }

    public function create()
    {
        return view('forum.create-topic');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        Topic::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('forum.index')->with('success', 'Topic created successfully!');
    }

    public function edit(Topic $topic)
    {
        if (Gate::denies('update', $topic)) {
            abort(403);
        }

        return view('forum.edit-topic', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        if (Gate::denies('update', $topic)) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        $topic->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('topic.show', $topic)->with('success', 'Topic updated successfully!');
    }

    public function destroy(Topic $topic)
    {
        if (Gate::denies('delete', $topic)) {
            abort(403);
        }

        $topic->delete();

        return redirect()->route('forum.index')->with('success', 'Topic deleted successfully!');
    }
}

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'location' => $request->location,
            'website' => $request->website,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');

        $user->update([
            'profile_picture' => $path,
        ]);

        return back()->with('success', 'Profile picture updated successfully!');
    }

    public function removeProfilePicture(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->update([
            'profile_picture' => null,
        ]);

        return back()->with('success', 'Profile picture removed successfully!');
    }
}