<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Searchable;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Invitation
 * @package Apithy
 */
class Invitation extends Model implements Sortable, Filterable, Searchable
{
    use SortTrait, FilterTrait;

    const INVITATION_PENDING = 0;

    const INVITATION_ACCEPTED = 1;

    const INVITATION_CANCELLED = 2;

    const TYPE_ENROLL = 'enroll';

    const TYPE_REGISTER = 'register';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitations';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'company_id' => 'integer',
        'user_id' => 'integer',
        'code' => 'string',
        'contact' => 'string',
        'status' => 'integer',
        'type' => 'string',
        'assignation_id' => 'integer',
        'student_id' => 'integer',
        'experience_id' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'contact',
        'role_id',
        'company_id',
        'user_id',
        'status',
        'type',
        'assignation_id',
        'student_id',
        'experience_id',
    ];

    protected $sortable = [
        'id',
        'role_id',
        'company_id',
        'user_id',
        'code',
        'contact',
        'created_at',
        'updated_at',
        'status',
        'type',
        'assignation_id',
        'student_id',
        'experience_id',
    ];

    /**
     * The attributes that can be filtered.
     *
     * @var array
     */
    protected $filterable = [
        'id',
        'role_id',
        'company_id',
        'code',
        'contact',
        'created_at',
        'updated_at',
        'status',
        'type',
        'assignation_id',
        'student_id',
        'experience_id',
    ];

    /**
     * @inheritdoc
     */
    public function scopeSearch($query, $term)
    {
        $query->where(function ($query) use ($term) {
            $query
                ->orWhere('code', 'like', '%'.$term.'%')
                ->orWhere('contact', 'like', '%'.$term.'%');
        });
    }

    public function scopeAllowed($query)
    {
        $auth=Auth::user();
        if ($auth->can('fetch', Invitation::class)) {
            if (!$auth->isSuper()) {
                $companyId = $auth->company[0]->id;

                $query->whereHas('company', function ($q) use ($companyId) {
                    $q->where('id', $companyId);
                });
            }
        }

        // If the user is an admin, don't add any extra where clauses, so everything is returned.
        return $query;
    }

    /**
     * The role that belongs to the invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The Company that belongs to the invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * The User that create the invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    // Functions

    public function getStatusCssColor()
    {
        switch ($this->status) {
            case 0:
                return 'apithy-color-status-inactive';
            case 1:
                return 'apithy-color-status-active';
            default:
                return 'is-danger';
        }
    }
}
