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
}
