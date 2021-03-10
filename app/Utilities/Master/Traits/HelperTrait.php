<?php

namespace Apithy\Utilities\Master\Traits;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Aws\Sns\SnsClient;

trait HelperTrait
{
    public static function hasEmail($email)
    {
        $eval = strpos($email, '@');
        return $eval !== false;
    }

    public static function isPhone($phone)
    {
        return !!preg_match("/^([0-9]){8,10}$/", $phone);
    }

    public static function getCompanyId(Request $request)
    {
        return $request->user()->company()->first()->id;
    }

    public static function getTwilioClient()
    {
        $sid    = env('TWILIO_ACCOUNT_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        return new Client($sid, $token);
    }

    public static function getSnsClient()
    {
        $sid    = env('TWILIO_ACCOUNT_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        return new SnsClient($sid, $token);
    }

    public static function getTwilioFromPhone()
    {
        return env('TWILIO_FROM');
    }

    public static function getAngleMessage()
    {
        return "¡Te esperamos! <br>
            Team aNGLE <br>
            <strong>a</strong>pithy 
            <strong>N</strong>ext 
            <strong>G</strong>eneration 
            <strong>L</strong>earning 
            <strong>E</strong>nvironment";
    }
}
