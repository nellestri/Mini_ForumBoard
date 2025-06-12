<?php

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
