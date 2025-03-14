<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinBoardReaction extends Model
{
    use HasFactory;

    protected $fillable = ['bulletinboard_post_id', 'type'];
}
