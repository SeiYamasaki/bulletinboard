@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>BulletinBoard 掲示板</h1>

        <form action="{{ url('/bulletinboard/posts') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="title" placeholder="タイトル" required>
            <textarea name="content" placeholder="投稿内容" required></textarea>
            <select name="category">
                <option value="経費">経費</option>
                <option value="税務調査">税務調査</option>
                <option value="クライアント問題">クライアント問題</option>
            </select>
            <input type="file" name="image">
            <label><input type="checkbox" name="is_anonymous"> 匿名で投稿</label>
            <button type="submit">投稿</button>
        </form>

        <h2>投稿一覧</h2>
        @foreach ($posts as $post)
            <div class="post">
                <h3>{{ $post->title }}</h3>
                <p>{{ $post->content }}</p>
                <p>カテゴリ: {{ $post->category }}</p>
                @if ($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="投稿画像" width="200">
                @endif
            </div>
        @endforeach
    </div>
@endsection
