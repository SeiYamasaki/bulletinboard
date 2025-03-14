@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>新規投稿</h1>
        <form action="{{ route('bulletinboard.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">内容</label>
                <textarea name="content" id="content" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">投稿する</button>
        </form>
    </div>
@endsection
