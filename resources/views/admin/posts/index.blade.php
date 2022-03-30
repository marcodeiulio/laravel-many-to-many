@extends('layouts.app')

@section('main_content')
<header class="d-flex justify-content-between align-items-center mb-4">
	<h1>I miei post</h1>
	<span>
		<a href="{{ route('admin.posts.create') }}" class="btn btn-big btn-outline-success">Create new post</a>
	</span>
</header>
@if(session('message'))
<div class="alert alert-{{ session('type') ?? 'info' }}">
	{{ session('message') }}
</div>
@endif
<table class="table table-dark">
	<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col">Title</th>
			<th scope="col">Category</th>
			<th scope="col">Slug</th>
			{{-- <th scope="col">Creato il</th> --}}
			<th scope="col">Modificato il</th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
		@forelse ($posts as $post)
		<tr>
			<th scope="row">{{ $post->id }}</th>
			<td>{{ $post->title }}</td>
			<td>
				@if($post->category)
				<span class="badge bg-{{ $post->category->color }} @if($post->category->color === 'warning' || $post->category->color === 'info' || $post->category->color === 'light') text-dark @endif">{{ $post->category->label }}</span>
				@else -
				@endif
			</td>
			<td>{{ $post->slug }}</td>
			{{-- <td>{{ $post->created_at }}</td> --}}
			<td>{{ $post->updated_at }}</td>
			<td class="d-flex justify-content-end align-items-center">
				<a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-sm btn-outline-info">Details</a>
				<a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-outline-warning mx-3">Edit</a>
				<form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST">
					@method('DELETE')
					@csrf
					<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
				</form>
			</td>
		</tr>
		@empty
		<tr>
			<td colspan="5">
				<h3>Non ci sono post.</h3>
			</td>
		</tr>
		@endforelse
	</tbody>
</table>
@endsection