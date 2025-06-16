<?php

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