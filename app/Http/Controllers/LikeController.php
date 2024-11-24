<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Feed;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request, $feedId)
    {
        $like = Like::firstOrCreate([
            'user_id' => auth()->id(),
            'feed_id' => $feedId,
        ]);

        return response()->json(['status' => 'success', 'liked' => true]);
    }

    public function unlike(Request $request, $feedId)
    {
        $like = Like::where('user_id', auth()->id())->where('feed_id', $feedId)->first();

        if ($like) {
            $like->delete();
            return response()->json(['status' => 'success', 'liked' => false]);
        }

        return response()->json(['status' => 'failed']);
    }
}
