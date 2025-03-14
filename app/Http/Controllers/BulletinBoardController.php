<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BulletinBoardPost;
use Illuminate\Support\Facades\Log;

class BulletinBoardController extends Controller
{
    /**
     * 投稿一覧を取得（Blade 用）
     */
    public function index()
    {
        $posts = BulletinBoardPost::latest()->get();
        return view('bulletinboard.index', compact('posts'));
    }

    /**
     * 投稿一覧を JSON で取得（Vue.js 用 API）
     */
    public function getPosts()
    {
        $posts = BulletinBoardPost::latest()->get([
            'id',
            'title',
            'content',
            'category',
            'is_anonymous',
            'image_path',
            'created_at'
        ]);

        if ($posts->isEmpty()) {
            return response()->json(['message' => '投稿がありません'], 404);
        }

        return response()->json($posts);
    }

    /**
     * 投稿作成フォームを表示
     */
    public function create()
    {
        return view('bulletinboard.create');
    }

    /**
     * 新しい投稿を保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'is_anonymous' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bulletinboard_images', 'public');
        }

        $post = BulletinBoardPost::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'] ?? '未分類', // デフォルト値を設定
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'image_path' => $imagePath,
        ]);

        if (!$post) {
            Log::error("投稿の保存に失敗しました", ['request' => $request->all()]);
            return back()->withErrors(['error' => '投稿の保存に失敗しました']);
        }

        return redirect()->route('bulletinboard.index')->with('success', '投稿が完了しました');
    }

    /**
     * 指定された投稿の詳細を表示
     */
    public function show(BulletinBoardPost $post)
    {
        return view('bulletinboard.show', compact('post'));
    }

    /**
     * 指定された投稿を削除
     */
    public function destroy(BulletinBoardPost $post)
    {
        $post->delete();

        return redirect()->route('bulletinboard.index')->with('success', '投稿を削除しました');
    }
}
