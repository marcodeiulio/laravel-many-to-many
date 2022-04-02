<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
                'image' => 'nullable|image',
                'tags' => 'nullable|exists:tags,id',
            ],
        );
        $data = $request->all();
        $post = new Post();

        if (array_key_exists('image', $data)) {
            $img_url = Storage::put('post_images', $data['image']);
            $data['image'] = $img_url;
        }

        $post->fill($data);
        $post->slug = Str::slug($post->title, '-');
        $post->save();

        // Una volta creato, aggiungo la relazione con i tag (lo faccio dopo averlo creato perché prima non avrei l'id)
        if (array_key_exists('tags', $data)) $post->tags()->attach($data['tags']);

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
        if (!str_starts_with($post->image, 'http')) {
            $post->image = asset('storage/' . $post->image);
        }

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

        $post_tags_ids = $post->tags->pluck('id')->toArray();

        if (!str_starts_with($post->image, 'http')) {
            $post->image = asset('storage/' . $post->image);
        }

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'post_tags_ids'));
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
                'image' => 'nullable|image',
                'tags' => 'nullable|exists:tags,id',
            ],
        );

        $data = $request->all();

        if (array_key_exists('image', $data)) {
            if ($post->image) Storage::delete($post->image);
            $img_url = Storage::put('post_images', $data['image']);
            $data['image'] = $img_url;
        }

        $data['slug'] = Str::slug($request->title, '-');

        $post->update($data);

        if (array_key_exists('tags', $data)) $post->tags()->sync($data['tags']);
        else $post->tags()->detach();

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
        // Elimino eventuali relazioni
        if (count($post->tags)) $post->tags()->detach();

        // Elimino eventuali immagini
        if ($post->image) Storage::delete($post->image);

        // Elimino il post
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "The post '$post->title' has been deleted.")->with('type', 'success');
    }
}
