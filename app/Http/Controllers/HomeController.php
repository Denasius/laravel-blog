<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
    	$posts = Post::paginate(2);

    	return view('pages.index', [
            'posts' => $posts
        ]);
    }

    // Показываю старницу поста по slug'у
    public function show($slug)
    {
    	$post = Post::where('slug', $slug)->firstOrFail();

    	return view('pages.show', compact('post'));
    }

    // Показываю все посты по выбранному тегу
    public function tag($slug)
    {
    	$tag = Tag::where('slug', $slug)->firstOrFail();

    	$posts = $tag->posts()->where('status', 0)->paginate(4);

    	return view('pages.list', ['posts' => $posts]);
    }

    // Показываю все посты по выбранной категории
    public function category($slug)
    {
    	$category = Category::where('slug', $slug)->firstOrFail();

    	$posts = $category->posts()->where('status', 0)->paginate(4);

    	return view('pages.list', ['posts' => $posts]);
    }
}
