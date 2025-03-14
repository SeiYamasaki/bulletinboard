@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->content }}</p>
        <p><strong>カテゴリ:</strong> {{ $post->category }}</p>
        <a href="{{ route('bulletinboard.index') }}" class="btn btn-secondary">戻る</a>
    </div>
@endsection
