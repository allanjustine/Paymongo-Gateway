<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['user_id', 'receiver_id', 'body'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyAttribute($value)
    {
        $text = e($value);

        $pattern = '/(https?:\/\/[\w\-._~:\/?#\[\]@!$&\'()*+,;=%]+|(?:www\.|(\w+\.)+(?:com|net|org|io|dev|app|co|ph|gov|edu|info|me|tv|ai))[\w\-._~:\/?#\[\]@!$&\'()*+,;=%]*)/i';

        return preg_replace_callback($pattern, function ($matches) {
            $url   = $matches[0];
            $href  = preg_match('/^https?:\/\//i', $url) ? $url : 'https://' . $url;
            return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" class="underline underline-offset-2 opacity-90 hover:opacity-100 break-all">' . $url . '</a>';
        }, $text);
    }
}
