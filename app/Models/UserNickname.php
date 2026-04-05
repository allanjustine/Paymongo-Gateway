<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNickname extends Model
{
    protected $fillable = ['setter_id', 'target_id', 'nickname'];
}
