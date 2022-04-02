@extends('layouts.app')
@section('main_content')
@if(session('message'))
<div class="alert alert-{{ session('type') ?? 'info' }}">
	{{ session('message') }}
</div>
@endif
<div class="row">
	<div class="col-12 d-flex justify-content-between">
		<a href="{{ route('admin.posts.index') }}" class="btn btn-big btn-outline-info">Back to Index</a>
		<div>
			<a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-big btn-outline-warning me-3">Edit</a>
			<form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline">
				@method('DELETE')
				@csrf
				<button type="submit" class="btn btn-big btn-outline-danger">Delete</button>
			</form>
		</div>
	</div>
</div>
<div class="d-flex justify-content-center">
	<div class="card bg-dark text-white" style="width: 18rem;">
		<img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}" class="card-img-top" alt="{{ $post->title }}">
		<div class="card-body">
			<h3 class="card-title">{{ $post->title }}
				<span class="badge bg-{{ $post->category->color }} @if($post->category->color === 'warning' || $post->category->color === 'info' || $post->category->color === 'light') text-dark @endif">{{ $post->category->label }}</span>
			</h3>
			<p class="card-text">{{ $post->content }}</p>
			<h5>Tags:
				@forelse ($post->tags as $tag)
				<span class="badge rounded-pill" style="background-color: {{ $tag->color }}">{{ $tag->label }}</span>
				@empty
				none
				@endforelse
			</h5>
		</div>
	</div>
</div>
@endsection