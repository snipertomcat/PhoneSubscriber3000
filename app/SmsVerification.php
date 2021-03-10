<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;

class SmsVerification extends Model
{

    const VERIFICATION_PENDING = 'pending';
    const VERIFICATION_CANCELED = 'canceled';
    const VERIFICATION_VERIFIED = 'verified';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sms_verifications';

    protected $guarded = [];

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = true;
}
