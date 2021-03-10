<?php

namespace Apithy\Services;

use Apithy\Experience;
use Apithy\Session;
use Apithy\SmsVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use AWS;

/**
 * Class RegisterService
 * @package Apithy\Services
 */
class SmsService
{
    public function createSmsVerification($data)
    {
        $code = rand(1000, 9999); //generate random code
        $data['code'] = $code; //add code in $request body
        SmsVerification::create($data);
    }

    public function sendSms($from = "apithy", $to = false, $message = false)
    {
        if ($to && $message) {
            try {
                $sms = AWS::createClient('sns');
                $sms->publish([
                    'Message' => $message,
                    'PhoneNumber' => $to,
                    'MessageAttributes' => [
                        'AWS.SNS.SMS.SMSType'  => [
                            'DataType'    => 'String',
                            'StringValue' => 'Promotional',
                        ]
                    ],
                ]);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
