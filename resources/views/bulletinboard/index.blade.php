@extends('layouts.app')

@section('content')
    <div>
        @if (Auth::check())
            {{ Auth::user()->name }}
        @else
            <p>ゲストユーザー</p>
        @endif
    </div>

    <div class="container">
        <h1>掲示板一覧</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('bulletinboard.create') }}" class="btn btn-primary">新規投稿</a>

        {{-- Vue.js の投稿一覧コンポーネント --}}
        <div id="app">
            <post-list></post-list>
        </div>
    </div>
@endsection

@section('vue')
    @vite(['resources/js/app.js'])
@endsection
