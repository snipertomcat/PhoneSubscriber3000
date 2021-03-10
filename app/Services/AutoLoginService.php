<?php

namespace Apithy\Services;

use Apithy\AutoLogin;
use Apithy\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class AutoLoginService
{

    private $lifetime = 1440;
    private $removeOnExpire = false;
    private $uniqueUse = false;

    public function __construct()
    {
        $this->lifetime = env('AUTO_LOGIN_LIFETIME', 1440);
        $this->removeOnExpire = env('AUTO_LOGIN_REMOVE_EXPIRED', false);
        $this->uniqueUse = env('AUTO_LOGIN_UNIQUE_USE', false);
    }

    /**
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function autoLogin(string $token)
    {
        $autoLogin = $this->getAutoLoginByToken($token);
        if ($autoLogin) {
            if ($this->autoLoginValid($autoLogin)) {
                \Auth::login($autoLogin->user, true);
                $this->deleteExpiredTokens();
                return redirect($autoLogin->path);
            }
        }
        return redirect(env('AUTO_LOGIN_REDIRECT_URL', '/'));
    }

    public function getAutoLoginByToken(string $token)
    {
        $autoLogin = AutoLogin::where('token', $token)
            ->first();
        return $autoLogin;
    }

    public function createAutoLogin(User $user, string $path)
    {
        $autoLogin = new AutoLogin();
        $token = $autoLogin->generateUniqueToken();
        $autoLogin->fill([
            'user_id'   => $user->id,
            'path'      => $path,
            'token'     => $token
        ]);
        $autoLogin->save();
        return $autoLogin;
    }

    public function updateAutoLoginPath(AutoLogin $autoLogin, string $path, $generateUrl = false)
    {
        $autoLogin->fill(['path' => $path]);
        $autoLogin->save();
        if ($generateUrl) {
            return $this->urlGenerator($autoLogin);
        }
        return $autoLogin;
    }

    public function to(User $user, string $path, bool $signedUrl = false)
    {
        $autoLogin = $this->createAutoLogin($user, $path);
        return $this->urlGenerator($autoLogin, $signedUrl);
    }

    public function urlGenerator(AutoLogin $autoLogin, bool $signedUrl = false)
    {
        if (!$signedUrl) {
            return route('autologin', ['token' => $autoLogin->token]);
        }
        if ($this->removeOnExpire) {
            return URL::temporarySignedRoute(
                'autologin',
                now()->addMinutes($this->lifetime),
                ['token' => $autoLogin->token]
            );
        }
        return URL::signedRoute('autologin', ['token' => $autoLogin->token]);
    }

    /**
     * @param AutoLogin $autoLogin
     * @return bool
     * @throws \Exception
     */
    public function autoLoginValid(AutoLogin $autoLogin): bool
    {
        $autoLogin->count = 1;
        $autoLogin->save();
        if ($this->removeOnExpire) {
            if ($autoLogin->created_at <= Carbon::now()->subMinutes($this->lifetime)) {
                $autoLogin->delete();
                return false;
            }
            if ($this->uniqueUse) {
                return !$autoLogin->count;
            }
        }
        return true;
    }

    /**
     * Eliminar tokens expirados
     */
    public function deleteExpiredTokens(): void
    {
        if ($this->removeOnExpire) {
            \DB::table('autologin_tokens')
                ->whereDate('created_at', '<=', Carbon::now()->subMinutes($this->lifetime))
                ->delete();
        }
    }
}
