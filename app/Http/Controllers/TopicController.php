<?php

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