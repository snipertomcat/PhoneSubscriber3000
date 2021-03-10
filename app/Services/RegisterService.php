<?php

namespace Apithy\Services;

use Apithy\Company;
use Apithy\CompanyArea;
use Apithy\CompanyPosition;
use Apithy\Country;
use Apithy\Exceptions\AlreadyRegisteredException;
use Apithy\Exceptions\UninvitedException;
use Apithy\ExperienceLicence;
use Apithy\Invitation;
use Apithy\Language;
use Apithy\Level;
use Apithy\Metadata;
use Apithy\PasswordRenew;
use Apithy\Role;
use Apithy\School;
use Apithy\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RegisterService
 * @package Apithy\Services
 */
class RegisterService
{
    /**
     * @var WebserviceService
     */
    protected $webservice;

    /**
     * @var boolean
     */
    protected $activeService;

    /**
     * RegisterService constructor.
     *
     * @param WebserviceService $webservice
     */
    public function __construct(WebserviceService $webservice)
    {
        $this->webservice = $webservice;
        $this->activeService = config('webservice.lms.active');
    }

    /**
     * Creates a user.
     *
     * @throws \Exception
     * @param array $data
     * @return \Apithy\User
     */
    public function createUser(array $data)
    {
        $company = null;

        if (isset($data['email'])) {
            if (User::where(['access' => $data['email']])->first()) {
                throw new AlreadyRegisteredException(trans('messages.already_registered_user'));
            }
        } else {
            if (User::where(['access' => $data['phone']])->first()) {
                throw new AlreadyRegisteredException(trans('messages.already_registered_user'));
            }
        }

        if (isset($data['company_info'])) {
            $company = Company::create(json_decode($data['company_info'], true));
        }

        if (!isset($data['password'])) {
            //$data['password'] = str_random(8);
            $data['password'] = 'secret';
        }

        $user = new User(array_merge($data, [
            'access' => (isset($data['email']) ? $data['email'] : $data['phone']),
            'password' => bcrypt($data['password']),
            'registration_method' => $data['register_type'],
        ]));

        if ($user->registrationMethodIs(User::REGISTRATION_ADMIN)
            || $user->registrationMethodIs(User::REGISTRATION_IMPORT_FILE)) {
            $user->confirmed_at = Carbon::now();
            $user->status = User::STATUS_SET_PASSWORD;
        }

        $user->activated = true;
        $company_attributes = [];

        if (isset($data['job_title'])) {
            $company_attributes['job_title'] = $data['job_title'];
        }

        if (isset($data['job_area'])) {
            $company_attributes['job_area'] = $data['job_area'];
        }

        if ($user->save()) {

            if ($user->registrationMethodIs(User::REGISTRATION_ADMIN)
                || $user->registrationMethodIs(User::REGISTRATION_IMPORT_FILE)) {
                $token = PasswordRenew::generateToken($user->id);
                PasswordRenew::savePasswordRenew(
                    ['user_id' => $user->id],
                    ['token' => $token]
                );
            }

            if (isset($data['role_id'])) {
                $user->roles()->attach($data['role_id']);
            } else {
                $user->roles()->attach(Role::STUDENT);
            }

            if (isset($data['company'])) {
                //$user->company()->attach($data['company_id'], $company_attributes);
                if (!isset($company)) {
                    $company = new Collection();
                    $company->id = $data['company'];
                }
            }
            if ($company) {
                $user->company()->attach($company->id, $company_attributes);
            } elseif (!empty($data['company_id']) && !$company) {
                $user->company()->attach($data['company_id']);
            } else {
                $user->company()->attach(Company::getDefautCompanyId());
            }
            // Sync with Taxonomy
            if (!empty($data['area'])) {
                $user->taxonomy()->attach($data['area']);
            }
            if (!empty($data['position'])) {
                $user->taxonomy()->attach($data['position']);
            }
            if (!empty($data['tag_ids'])) {
                $user->taxonomy()->attach($data['tag_ids']);
            }
            /*
            if (isset($data['area']) && isset($data['position'])) {
                $user->position()->attach($data['position'], [
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
            }
            */
        }

        return $user->fresh();
    }

    /**
     * Request webservice data or update an existing one.
     *
     * @param string $access
     * @param string $password
     * @return \Apithy\User
     */
    public function updateOrCreateLMSUser($access, $password)
    {
        $data = collect($this->webservice->request($access, $password));

        /* @var User $user */
        $user = User::firstOrNew([
            'access' => $access,
            'registration_method' => User::REGISTRATION_LMS,
        ]);

        $user->fill($data->only([
            'name',
            'surname',
            'personal_code',
            'email',
            'birthday',
        ])->toArray());


        $school = !empty($data['active_school'])
            ? $this->createSchool($data['active_school'])
            : null;

        if ($school) {
            $user->school()->associate($school);
        } else {
            $user->school()->dissociate();
        }

        $level = $school && !empty($data['active_school']['active_period'])
            ? $this->createLevel($data['active_school']['active_period'])
            : null;

        if ($level) {
            $user->level()->associate($level);
        } else {
            $user->level()->dissociate();
        }

        $profile = $level && !empty($data['active_school']['active_period']['profile_code'])
            ? $this->fetchRole($data['active_school']['active_period']['profile_code'])
            : null;

        $user->setPassword($password);

        if (!$user->exists) {
            $user->activated = true;
        }

        $user->save();

        if ($profile) {
            $user->roles()->sync([$profile->id]);
        } else {
            $user->roles()->detach();
        }

        $user->fresh(['metadata']);

        $user->metadataFor(Metadata::LMS_RESPONSE, $data['meta']);

        return $user;
    }

    /**
     * Creates a invited user.
     *
     * @throws \Exception
     * @param array $data
     * @return \Apithy\User
     */
    public function createInvitationUser(array $data)
    {
        $invitation = Invitation::where([
            'code' => $data['invitation_code'],
            'contact' => $data['email'],
        ])
            ->orWhere([
                'code' => $data['invitation_code'],
                'contact' => $data['phone'],
            ])
            ->first();

        if (!$invitation) {
            throw new UninvitedException(trans('messages.no_invitation_found'));
        }

        if (User::where(['access' => $data['email']])->orWhere(['access' => $data['phone']])->first()) {
            throw new AlreadyRegisteredException(trans('messages.already_registered_user'));
        }

        $user = new User(array_merge($data, [
            'access' => $data['email'] ?? $data['phone'],
            'password' => bcrypt($data['password']),
            'registration_method' => User::REGISTRATION_INVITATION,
        ]));

        $user->activated = true;

        if ($user->save()) {
            if ($invitation->role_id) {
                $user->roles()->attach($invitation->role_id);
            } else {
                $user->roles()->attach(Role::STUDENT);
            }

            if ($invitation->company_id) {
                $user->company()->attach($invitation->company_id);
            }

            $licence = ExperienceLicence::where('email', $user->access)
                ->where('experience_id', $invitation->experience_id)
                ->where('status', ExperienceLicence::STATUS_WAITING_CONFIRMATION)
                ->first();
            if ($licence) {
                $licence->user_id = $user->id;
                $licence->status = ExperienceLicence::STATUS_UNAVAILABLE;
                $licence->save();
            }

            $invitation->status = Invitation::INVITATION_ACCEPTED;
            $invitation->save();
        }


        return $user->fresh();
    }

    /**
     * Creates school or fetches from data provided.
     *
     * @param array $data
     * @return mixed
     */
    protected function createSchool(array $data)
    {
        $school = School::firstOrNew(['code' => $data['code']], $data);

        if (!$school->exists) {
            $country = Country::firstOrCreate(['code' => $data['country']['code']], $data['country']);
            $language = Language::firstOrCreate(['code' => $data['language']['code']], $data['language']);
            $school->country()->associate($country);
            $school->language()->associate($language);
            $school->save();
        }

        return $school;
    }

    /**
     * Creates level or fetches from data provided.
     *
     * @param array $data
     * @return mixed
     */
    protected function createLevel(array $data)
    {
        return Level::firstOrCreate(['code' => $data['level_code']], [
            'code' => $data['level_code'],
            'name' => $data['level_name'],
            'remote_id' => $data['level_id'],
        ]);
    }

    /**
     * Fetch existing role.
     *
     * @param string $code
     * @return mixed
     */
    protected function fetchRole($code)
    {
        return Role::where('code', $code)->first();
    }

    /**
     * Validate the information for company, job_area and job_position.
     *
     * @param $data
     * @return mixed
     */
    public function validateCompany($data)
    {
        $company = null;
        $area = null;
        $position = null;

        if (isset($data['company'])) {
            $company = Company::where('name', $data['company'])->first();
            if (!$company) {
                $data['company'] = Company::getDefautCompanyId();
            } else {
                $data['company'] = $company->id;

                $area = $company->areas()->where('name', $data['area'])->first();

                if (!$area && isset($data['area'])) {
                    // crear área.
                    $area = new CompanyArea([
                        'company_id' => $company->id,
                        'name' => $data['area']
                    ]);

                    $area->save();
                }

                if ($area) {
                    $data['area'] = $area->id;

                    $position = $area->positions()->where('name', $data['position'])->first();

                    if (!$position && isset($data['position'])) {
                        // crear posición
                        $position = new CompanyPosition([
                            'area_id' => $area->id,
                            'name' => $data['position']
                        ]);
                        $position->save();
                    }

                    if ($position) {
                        $data['position'] = $position->id;
                    }
                }
            }
        }
        return $data;
    }
}
