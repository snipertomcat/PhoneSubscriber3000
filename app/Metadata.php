<?php

namespace Apithy;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Metadata
 * @package Apithy
 */
class Metadata extends Model
{
    /**
     *  Key name for LMS response data.
     */
    const LMS_RESPONSE = 'LMS_RESPONSE';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'metadata';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'key' => 'string',
        'value' => 'json',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get all of the owning metadatable models.
     */
    public function metadatable()
    {
        return $this->morphTo();
    }
}
