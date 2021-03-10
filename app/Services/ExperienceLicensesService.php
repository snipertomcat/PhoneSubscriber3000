<?php

namespace Apithy\Services;

use Apithy\Events\LicenseRemoved;
use Apithy\Company;
use Apithy\Experience;
use Apithy\ExperienceLicence;
use Apithy\ExperienceLicenseSetting;
use Apithy\Http\Resources\ExperienceLicenses\PendingLicensesResource;
use Apithy\Http\Resources\ExperienceLicenses\UsedLicensesResource;
use Apithy\Http\Resources\Experiences\ExperienceLicenseTableResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExperienceLicensesService
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getExperienceLicensesList()
    {
        $experienses = Experience::allowed()
            ->whereHas('licences', function ($query) {
                $query->BuyedLicenses();
            })
            ->licensesCount()
            ->pendingLicensesCount()
            ->usedLicensesCount()
            ->unusedLicensesCount()
            ->when($this->request->filled('search'), function ($query) {
                return $query->search($this->request->input('search'));
            })
            ->paginate($this->request->input('per_page', 15));
        return ExperienceLicenseTableResource::collection($experienses);
    }

    public function getExperienceLicense($id)
    {
        $experiense = Experience::allowed()
            ->licensesCount()
            ->pendingLicensesCount()
            ->usedLicensesCount()
            ->unusedLicensesCount()
            ->where('id', $id)
            ->firstOrFail();
        return ExperienceLicenseTableResource::make($experiense);
    }

    public function getExperiencePendingLicenses($id)
    {
        $licenses = ExperienceLicence::whereHas('experience', function ($query) use ($id) {
            $query->allowed()
                ->where('id', $id);
        })
            ->where('status', ExperienceLicence::STATUS_WAITING_CONFIRMATION)
            ->BuyedLicenses()
            ->when($this->request->filled('search'), function ($query) {
                return $query->where('email', 'like', "%{$this->request->input('search')}%");
            })
            ->paginate($this->request->input('per_page', 15));
        return PendingLicensesResource::collection($licenses);
    }

    public function unsetExperienceLicense($id)
    {
        $licence = ExperienceLicence::where([
            ['id', $id],
            ['status', ExperienceLicence::STATUS_WAITING_CONFIRMATION]
        ])
            ->BuyedLicenses()
            ->firstOrFail();
        $email = $licence->email;
        $licence->email = null;
        $licence->status = ExperienceLicence::STATUS_AVAILABLE;
        /*
        $licence->expiration_active = 0;
        $licence->expiration_start = null;
        $licence->expiration_ends = null;
        $licence->day_left = null;
       */
        $licence->saveOrFail();
        $company_logo = Company::getDomainLogo($this->request);
        event(new LicenseRemoved($email, $company_logo, 'Invitacion eliminada'));
        return $this->getExperiencePendingLicenses($licence->experience_id);
    }

    public function usedExperienceLicense($id)
    {
        $licenses = ExperienceLicence::whereHas('experience', function ($query) use ($id) {
            $query->allowed()
                ->where('id', $id);
        })
            ->where('status', ExperienceLicence::STATUS_UNAVAILABLE)
            ->BuyedLicenses()
            ->when($this->request->filled('search'), function ($query) {
                return $query->whereHas('user', function ($q) {
                    return $q->where('name', 'like', "%{$this->request->input('search')}%");
                });
            })
            ->paginate($this->request->input('per_page', 15));
        return UsedLicensesResource::collection($licenses);
    }

    public function setExperienceLicenseSettings(Request $request)
    {
        $experience_id = $request->get('experience_id', null);
        if (!isset($experience_id)) {
            return response()->json(['message' => 'Missing experience id'], 409);
        }
        $days = $request->get('days', null);
        $target = $request->get('target', 'default');
        $setting = Experience::find($experience_id)->licensesSetting;
        $enable_settings = $request->get('enable_settings', false);
        $licenses = ExperienceLicence::where('experience_id', $experience_id)
            ->buyedLicenses()
            ->where('status', ExperienceLicence::STATUS_WAITING_CONFIRMATION)
            ->get();

        if (!isset($setting)) {
            $setting = new ExperienceLicenseSetting([
                'expiration_active' => false,
                'days' => $days,
                'experience_id' => $experience_id,
                'company_id' => $request->user()->company()->first()->id
            ]);
            $setting->save();
        }

        switch ($target) {
            case 'get':
                // Get the settings
                $data = [
                    'enable' => $setting->expiration_active,
                    'days' => (isset($setting->days)) ? $setting->days : 0,
                    'experience_id' => $setting->experience_id
                ];

                return response()->json($data, 200);
                break;
            case 'set':
                if ($enable_settings && isset($days) && $days > 0 && $days < 31) {
                // Set the setting
                    $setting->expiration_active = $enable_settings;
                    $setting->days = $days;
                    $setting->save();
                // Set the settings to licenses
                    $start = Carbon::now();
                    $ends = Carbon::now()->addDays($days);
                    try {
                        $result = ExperienceLicence::whereIn('id', $licenses->pluck('id'))
                            ->update([
                                'expiration_start' => $start,
                                'expiration_ends' => $ends,
                                'day_left' => $start->diffInDays($ends)
                            ]);

                        return response()->json(['message' => 'success', 'affected_rows' => $result], 200);
                    } catch (\Exception $e) {
                        return response()->json(['message' => $e->getMessage()], 500);
                    }
                } else {
                    // Error
                    return response()->json([
                        'message' => 'The value must be between 1 and 30',
                        'affected_rows' => 0,
                        'break_action' => true
                    ], 200);
                }
                break;
            case 'unset':
                // Unset the settings to licenses
                break;
            default:
                // Turn on/off the settings flag
                if (isset($licenses)) {
                    try {
                        $setting->update(['expiration_active' => $enable_settings]);
                        $result = ExperienceLicence::whereIn('id', $licenses->pluck('id'))
                            ->update(['expiration_active' => $enable_settings]);
                        return response()->json(['message' => 'success', 'affected_rows' => $result], 200);
                    } catch (\Exception $e) {
                        return response()->json(['message' => $e->getMessage()], 500);
                    }
                }
                break;
        }
    }
}
