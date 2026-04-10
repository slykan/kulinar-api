<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('user:id,name,avatar')
            ->where('published', true);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(12);

        $user = $request->user();
        $postIds = $posts->pluck('id')->all();

        // Ratings - safe fallback ako tablica ne postoji
        $ratingsAvg  = collect();
        $ratingsCount = collect();
        $myRatings   = collect();
        try {
            $ratingsData = \DB::table('ratings')
                ->whereIn('post_id', $postIds)
                ->selectRaw('post_id, AVG(rating) as avg_rating, COUNT(*) as cnt')
                ->groupBy('post_id')
                ->get()
                ->keyBy('post_id');
            $ratingsAvg  = $ratingsData->map(fn($r) => round($r->avg_rating, 1));
            $ratingsCount = $ratingsData->map(fn($r) => (int) $r->cnt);
            if ($user) {
                $myRatings = \DB::table('ratings')
                    ->where('user_id', $user->id)
                    ->whereIn('post_id', $postIds)
                    ->pluck('rating', 'post_id');
            }
        } catch (\Exception $e) {}

        $postsArray = $posts->toArray();
        $postsArray['data'] = collect($postsArray['data'])->map(function ($post) use ($user, $ratingsAvg, $ratingsCount, $myRatings) {
            $post['rating_average'] = $ratingsAvg->get($post['id'], 0);
            $post['rating_count']   = $ratingsCount->get($post['id'], 0);
            $post['my_rating']      = $myRatings->get($post['id']);
            $post['is_owner']       = $user ? $post['user_id'] === $user->id : false;
            return $post;
        })->values()->all();

        return response()->json($postsArray);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image'   => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = $request->user()->posts()->create([
            'title'        => $data['title'],
            'slug'         => Str::slug($data['title']),
            'excerpt'      => $data['excerpt'] ?? null,
            'content'      => $data['content'],
            'image'        => $imagePath,
            'published'    => true,
            'published_at' => now(),
        ]);

        return response()->json($post->load('user:id,name,avatar'), 201);
    }

    public function show(Request $request, string $slug)
    {
        $post = Post::with('user:id,name,avatar')
            ->where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $data = $post->toArray();
        $data['is_owner'] = $request->user()?->id === $post->user_id;
        $data['is_bookmarked'] = $request->user()
            ? $request->user()->bookmarks()->where('post_id', $post->id)->exists()
            : false;
        $data['rating_average'] = round($post->ratings()->avg('rating') ?? 0, 1);
        $data['rating_count']   = $post->ratings()->count();
        $data['my_rating']      = $request->user()
            ? $post->ratings()->where('user_id', $request->user()->id)->value('rating')
            : null;

        return response()->json($data);
    }

    public function update(Request $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Nedovoljna prava.'], 403);
        }

        $data = $request->validate([
            'title'   => 'sometimes|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'sometimes|string',
            'image'   => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $post->update($data);

        return response()->json($post->load('user:id,name,avatar'));
    }

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Nedovoljna prava.'], 403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Recept obrisan.']);
    }

    public function myPosts(Request $request)
    {
        $user = $request->user();
        $query = $user->posts()->with('user:id,name,avatar');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(12);
        $postIds = $posts->pluck('id')->all();

        $ratingsAvg  = collect();
        $ratingsCount = collect();
        $myRatings   = collect();
        try {
            $ratingsData = \DB::table('ratings')
                ->whereIn('post_id', $postIds)
                ->selectRaw('post_id, AVG(rating) as avg_rating, COUNT(*) as cnt')
                ->groupBy('post_id')
                ->get()
                ->keyBy('post_id');
            $ratingsAvg  = $ratingsData->map(fn($r) => round($r->avg_rating, 1));
            $ratingsCount = $ratingsData->map(fn($r) => (int) $r->cnt);
            $myRatings = \DB::table('ratings')
                ->where('user_id', $user->id)
                ->whereIn('post_id', $postIds)
                ->pluck('rating', 'post_id');
        } catch (\Exception $e) {}

        $postsArray = $posts->toArray();
        $postsArray['data'] = collect($postsArray['data'])->map(function ($post) use ($ratingsAvg, $ratingsCount, $myRatings) {
            $post['rating_average'] = $ratingsAvg->get($post['id'], 0);
            $post['rating_count']   = $ratingsCount->get($post['id'], 0);
            $post['my_rating']      = $myRatings->get($post['id']);
            $post['is_owner']       = true;
            return $post;
        })->values()->all();

        return response()->json($postsArray);
    }
}
