<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AutoLogin extends Model
{
    protected $table = 'autologin_tokens';

    protected $casts = [
        'id'            => 'integer',
        'user_id'       => 'integer',
        'token'         => 'string',
        'path'          => 'string',
        'count'         => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    protected $fillable = [
        'user_id',
        'token',
        'path',
        'count'
    ];

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Functions

    public function generateUniqueToken(): string
    {
        $token = Str::random(env('AUTO_LOGIN_TOKEN_LENGTH', 20));
        if (AutoLogin::where('token', $token)->get()->isNotEmpty()) {
            return $this->generateUniqueToken();
        }
        return $token;
    }
}
