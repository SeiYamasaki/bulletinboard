<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinBoardPost extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'category', 'is_anonymous', 'image_path'];
}
