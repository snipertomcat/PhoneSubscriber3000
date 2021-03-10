<?php

namespace Apithy;

use Apithy\Payments\Billable as BillableTrait;
use Apithy\Payments\Contracts\Billable;
use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Country
 * @package Apithy
 */
class Transaction extends Model implements Sortable, Filterable, Billable
{
    use SortTrait, FilterTrait, BillableTrait, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $guarded=[];

    protected $appends=[
        'status_text'
    ];


    /**
     * Return the user of this PaymentInformation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function details()
    {
        return $this->hasMany(TransactionDetails::class);
    }

    public function paymentSource()
    {
        return $this->belongsTo(PaymentInformation::class, 'user_payment_information_id');
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return "Pagado";
    }

    public function routeNotificationForMail()
    {
        return $this->user()->first()->email;
    }
}
