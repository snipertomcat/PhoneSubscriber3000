<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordRenew extends Model
{
    protected $casts = [
        'id'        => 'integer',
        'user_id'   => 'integer',
        'token'     => 'string'
    ];

    protected $fillable = [
        'user_id'
    ];

    const PASSWORD_REGEX = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/";

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function resolveRouteBinding($value)
    {
        return $this->where($this->getRouteKeyName(), $value)
            ->whereNotNull($this->getRouteKeyName())
            ->first();
    }

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function savePasswordRenew(array $fillable, array $forceFill = []): PasswordRenew
    {
        $passwordRenew = new PasswordRenew();
        $passwordRenew->fill($fillable);
        $passwordRenew->forceFill($forceFill);
        $passwordRenew->saveOrFail();
        return $passwordRenew;
    }

    // STATICS Functions

    public static function generateToken(int $userId): string
    {
        $token = Str::random(16);
        $token = "{$token}.$userId";
        return md5($token);
    }

    // Rules

    public static function storeRules(): array
    {
        return [
            'token'         => 'required|string',
            'password'      => 'required|confirmed|min:8|regex:'.static::PASSWORD_REGEX,
            'privacy'       => 'required|accepted',
            'conditions'    => 'required|accepted'
        ];
    }
}
