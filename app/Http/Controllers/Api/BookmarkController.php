<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();
        $existing = $user->bookmarks()->where('post_id', $post->id)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['bookmarked' => false]);
        }

        $user->bookmarks()->create(['post_id' => $post->id]);
        return response()->json(['bookmarked' => true]);
    }

    public function index(Request $request)
    {
        $posts = $request->user()
            ->bookmarkedPosts()
            ->with('user')
            ->latest()
            ->paginate(15);

        return response()->json($posts);
    }
}
