<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function rate(Request $request, Post $post)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            ['user_id' => $request->user()->id, 'post_id' => $post->id],
            ['rating' => $data['rating']]
        );

        return response()->json([
            'average' => round($post->ratings()->avg('rating'), 1),
            'count'   => $post->ratings()->count(),
            'mine'    => $data['rating'],
        ]);
    }
}
