<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post = new Post();
        return view('admin.posts.create', compact('categories', 'post', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|unique:posts|min:5|max:100',
                'content' => 'required|string',
                'category_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|url',
                'tags' => 'nullable|exists:tags,id',
            ],
        );

        $post = new Post();

        $post->fill($request->all());
        $post->slug = Str::slug($post->title, '-');
        $post->save();

        return redirect()->route('admin.posts.index')->with('message', "Il post '$post->title' è stato creato con successo!")->with('type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        $request->validate(
            [
                'title' => 'required|string|min:5|max:100',
                'content' => 'required|string',
                'category_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|url',
                'tags' => 'nullable|exists:tags,id',
            ],
        );

        $data = $request->all();

        $data['slug'] = Str::slug($request->title, '-');

        $post->update($data);

        return redirect()->route('admin.posts.show', $post)->with('message', 'The post has been successfully updated.')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "The post '$post->title' has been deleted.")->with('type', 'success');
    }
}
