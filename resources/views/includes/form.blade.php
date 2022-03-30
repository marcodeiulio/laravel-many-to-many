@extends('layouts.app')

@section('main_content')
<a href="{{ route('admin.posts.index') }}" class="btn btn-big btn-outline-info mb-4">Back to Index</a>
@if($post->exists)
<form action="{{ route('admin.posts.update', $post->id) }}" method="POST" class="row">
	@method('PUT')
	@else
	<form action="{{ route('admin.posts.store') }}" method="POST" class="row">
		@endif
		@csrf
		<div class="col-8 mb-3">
			<label for="title" class="form-label">Title</label>
			<input type="text" name="title" class="form-control" id="title" value="{{ $post->title }}">
		</div>
		<div class="col-4 mb-3 d-flex align-items-end">
			<select class="form-select" name="category_id">
				<option value="">No Categories</option>
				@foreach($categories as $c)
				<option value="{{ $c->id }}" @if($post->category_id == $c->id) selected @endif>{{ $c->label }}</option>
				@endforeach
			</select>
		</div>
		<div class="col-12 mb-3">
			<label for="content" class="form-label">Content</label>
			<textarea name="content" class="form-control" id="content" rows="10">{{ $post->content }}</textarea>
		</div>
		<div class="col-12 mb-3">
			<label for="image" class="form-label">Image URL</label>
			<input type="text" name="image" class="form-control" id="image" placeholder="http://placeholder.jpg" value="{{ $post->image }}">
		</div>
		<div class="row">
			<div class="col-12 d-flex justify-content-end">
				<button type="reset" class="btn btn-sm btn-outline-danger text-end me-5">Reset</button>
				<button type="submit" class="btn btn btn-outline-success text-end">Confirm</button>
			</div>
		</div>
	</form>

	@endsection