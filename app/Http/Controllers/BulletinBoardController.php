<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BulletinBoardPost;
use App\Models\BulletinBoardComment;
use App\Models\BulletinBoardReaction;

class BulletinBoardController extends Controller
{
    public function index()
    {
        $posts = BulletinBoardPost::latest()->get();
        return view('bulletinboard.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $post = new BulletinBoardPost();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->category = $request->category;
        $post->is_anonymous = $request->has('is_anonymous');
        
        if ($request->hasFile('image')) {
            $post->image_path = $request->file('image')->store('uploads', 'public');
        }

        $post->save();

        return redirect('/bulletinboard')->with('success', '投稿が完了しました！');
    }
}
