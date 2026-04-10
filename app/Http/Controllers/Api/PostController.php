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

        return response()->json($posts);
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
        $query = $request->user()->posts();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(12);

        return response()->json($posts);
    }
}
