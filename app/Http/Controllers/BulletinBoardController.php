<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BulletinBoardPost;
use App\Models\Like;
use Illuminate\Support\Facades\Log;
use App\Events\LikeUpdated;
use Illuminate\Support\Facades\Auth;

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
        try {
            $posts = BulletinBoardPost::latest()->withCount('likes')->get([
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
        } catch (\Exception $e) {
            Log::error('getPosts() エラー: ' . $e->getMessage());
            return response()->json(['message' => 'サーバーエラーが発生しました'], 500);
        }
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
        try {
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
                'category' => $validated['category'] ?? '未分類',
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'image_path' => $imagePath,
            ]);

            return redirect()->route('bulletinboard.index')->with('success', '投稿が完了しました');
        } catch (\Exception $e) {
            Log::error("投稿の保存に失敗しました: " . $e->getMessage());
            return back()->withErrors(['error' => '投稿の保存に失敗しました']);
        }
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
        try {
            $post->delete();
            return redirect()->route('bulletinboard.index')->with('success', '投稿を削除しました');
        } catch (\Exception $e) {
            Log::error("投稿削除エラー: " . $e->getMessage());
            return back()->withErrors(['error' => '投稿の削除に失敗しました']);
        }
    }

    /**
     * いいねボタンの処理（WebSocket 対応）
     */
    public function likePost(BulletinBoardPost $post)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json(['message' => 'ログインが必要です'], 401);
            }

            $like = Like::where('post_id', $post->id)->where('user_id', $userId)->first();

            if ($like) {
                $like->delete();
            } else {
                Like::create([
                    'post_id' => $post->id,
                    'user_id' => $userId,
                ]);
            }

            broadcast(new LikeUpdated($post));

            return response()->json(['likes_count' => $post->likes()->count()]);
        } catch (\Exception $e) {
            Log::error("いいね処理エラー: " . $e->getMessage());
            return response()->json(['message' => 'いいねの処理に失敗しました'], 500);
        }
    }
}
