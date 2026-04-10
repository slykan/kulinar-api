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

        $recentPostsQuery = Post::where('published', true)->latest()->take(2);
        $recentPostItems = $recentPostsQuery->get(['id', 'title', 'excerpt', 'slug', 'image']);
        $recentPostIds = $recentPostItems->pluck('id')->all();

        $ratingsData = collect();
        try {
            $ratingsData = \DB::table('ratings')
                ->whereIn('post_id', $recentPostIds)
                ->selectRaw('post_id, AVG(rating) as avg_rating, COUNT(*) as cnt')
                ->groupBy('post_id')
                ->get()
                ->keyBy('post_id');
        } catch (\Exception $e) {}

        $recentPosts = $recentPostItems->map(fn($p) => [
            'id'             => $p->id,
            'title'          => $p->title,
            'excerpt'        => $p->excerpt ? mb_strimwidth($p->excerpt, 0, 60, '…') : null,
            'slug'           => $p->slug,
            'image'          => $p->image,
            'rating_average' => $ratingsData->has($p->id) ? round($ratingsData->get($p->id)->avg_rating, 1) : 0,
            'rating_count'   => $ratingsData->has($p->id) ? (int) $ratingsData->get($p->id)->cnt : 0,
        ]);

        return response()->json([
            'user_count'   => $userCount,
            'post_count'   => $postCount,
            'recent_users' => $recentUsers,
            'recent_posts' => $recentPosts,
        ]);
    }
}
