<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blog = config('blog');

        return view('blog.index', [
            'sectionTitle' => $blog['section_title'],
            'sectionIntro' => $blog['section_intro'],
            'posts' => array_values($blog['posts']),
        ]);
    }

    public function show(string $slug): View
    {
        $posts = config('blog.posts');
        $post = $posts[$slug] ?? null;

        if (! $post) {
            abort(404);
        }

        return view('blog.show', [
            'post' => $post,
            'sectionTitle' => config('blog.section_title'),
            'related' => collect($posts)
                ->reject(fn ($item) => $item['slug'] === $slug)
                ->values()
                ->all(),
        ]);
    }
}
