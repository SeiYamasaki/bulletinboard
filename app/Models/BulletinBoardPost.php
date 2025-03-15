<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinBoardPost extends Model
{
    use HasFactory;

    protected $table = 'bulletin_board_posts'; // ✅ 修正したテーブル名を指定

    protected $fillable = [
        'title',
        'content',
        'category',
        'is_anonymous',
        'image_path'
    ];
    /**
     * 投稿に対する「いいね」のリレーション
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }
}
