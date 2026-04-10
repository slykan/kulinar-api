<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class StatsController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $postCount = Post::where('published', true)->count();

        $recentUsers = User::latest()
            ->take(4)
            ->get(['id', 'name', 'avatar'])
            ->map(fn($u) => [
                'id'       => $u->id,
                'name'     => $u->name,
                'avatar'   => $u->avatar,
                'initials' => mb_strtoupper(mb_substr($u->name, 0, 1)),
            ]);

        $recentPosts = Post::where('published', true)
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->latest()
            ->take(2)
            ->get()
            ->map(fn($p) => [
                'id'             => $p->id,
                'title'          => $p->title,
                'excerpt'        => $p->excerpt ? mb_strimwidth($p->excerpt, 0, 60, '…') : null,
                'slug'           => $p->slug,
                'image'          => $p->image,
                'rating_average' => round($p->ratings_avg_rating ?? 0, 1),
                'rating_count'   => $p->ratings_count ?? 0,
            ]);

        return response()->json([
            'user_count'   => $userCount,
            'post_count'   => $postCount,
            'recent_users' => $recentUsers,
            'recent_posts' => $recentPosts,
        ]);
    }
}
