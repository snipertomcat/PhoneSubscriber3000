<?php

namespace Apithy\Mail;

use Apithy\Experience;
use Apithy\Invitation;
use Apithy\Transaction;
use Apithy\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invoice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $transaction;
    public $experience;
    public $user;
    public $use_for;
    public $login_link;

    /**
     * Create a new message instance.
     *
     * @param Model $invitation
     * @param Experience $experience
     * @param User $user
     */

    public function __construct(Transaction $transaction, User $user, $use_for)
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->use_for = $use_for;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown(
            'mails.html.invoice',
            ['transaction' => $this->transaction,'user' =>$this->user,'use_for' => $this->use_for]
        );
    }
}
