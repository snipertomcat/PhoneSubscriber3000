<?php

namespace Apithy\Events;

use Apithy\Experience;
use Apithy\Transaction;
use Apithy\User;

class UserPayment
{
    public $transaction;
    public $user;

    /**
     * ExperienceEnrolled constructor.
     *
     * @param Experience $experience
     * @param User $user
     * @return void
     */
    public function __construct(Transaction $transaction, User $user, $use_for)
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->use_for=$use_for;
    }
}
