<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $feedId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'feed_id' => $feedId,
            'comment' => $request->comment,
        ]);

        return response()->json(['status' => 'success', 'comment' => $comment]);
    }
}
