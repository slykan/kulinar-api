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
        $postCount = Post::count();

        $recentUsers = User::latest()
            ->take(4)
            ->get(['id', 'name', 'avatar'])
            ->map(fn($u) => [
                'id'     => $u->id,
                'name'   => $u->name,
                'avatar' => $u->avatar,
                'initials' => mb_strtoupper(mb_substr($u->name, 0, 1)),
            ]);

        return response()->json([
            'user_count'   => $userCount,
            'post_count'   => $postCount,
            'recent_users' => $recentUsers,
        ]);
    }
}
