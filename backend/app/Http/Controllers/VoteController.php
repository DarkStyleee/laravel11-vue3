<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function vote(Request $request, $postId)
    {
        $request->validate([
            'value' => 'required|in:1,-1,0', // 1 для like, -1 для dislike, 0 для удаления
        ]);

        $post = Post::findOrFail($postId);
        $user = Auth::user();
        $value = $request->input('value');

        $vote = Vote::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($vote) {
            if ($value === 0) {
                $vote->delete();
                return response()->json(['message' => 'Голос удален'], 200);
            } else {
                $vote->update(['value' => $value]);
                return response()->json(['message' => 'Голос обновлен'], 200);
            }
        } else {
            if ($value !== 0) {
                Vote::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'value' => $value,
                ]);
                return response()->json(['message' => 'Голос добавлен'], 201);
            } else {
                return response()->json(['message' => 'Голос не найден'], 404);
            }
        }
    }

    public function getVotes($postId)
    {
        $post = Post::findOrFail($postId);
        $likes = $post->likes()->count();
        $dislikes = $post->dislikes()->count();

        return response()->json([
            'likes' => $likes,
            'dislikes' => $dislikes,
        ], 200);
    }
}