<?php

namespace Apithy\Services;

use Apithy\Exceptions\WebserviceException;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

/**
 * Class WebserviceService
 * @package Apithy\Services
 */
class WebserviceService
{
    /**
     * Webservice url.
     *
     * @var string
     */
    protected $url;

    /**
     * Guzzle client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * WebserviceService constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = config('webservice.lms.url');
    }

    /**
     * Fetch response data from webservice response.
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    public function request($username, $password)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'user' => $username,
                'password' => $password,
            ]
        ]);

        $body = $response->getBody()->getContents();

        return $this->parse(json_decode($body, true));
    }


    /**
     * Parse the content of the webservice response into a more
     * readable way.
     *
     * @param array $data
     * @return array
     * @throws WebserviceException
     */
    protected function parse(array $data)
    {
        if (empty($data)) {
            throw new WebserviceException(trans('messages.lms_webservice_unavailable'));
        }

        if (array_key_exists('error', $data)) {
            $error = collect($data['error'])->first();
            $message = trans(sprintf('messages.lms_webservice_error_%s', $error['errorCode']));
            throw new WebserviceException($message);
        }

        if (!array_key_exists('person', $data)) {
            throw new WebserviceException(trans('messages.lms_webservice_unavailable'));
        }

        $person = $data['person'];

        $birthday = sprintf('%04d-%02d-%02d', $person['birthYear'], $person['birthMonth'], $person['birthDay']);

        $schools = collect($this->parseWebserviceSchools($person['schools']));

        $parsed = [
            'remote_id' => $person['personId'],
            'access' => $person['user'],
            'name' => $person['name'],
            'surname' => $person['surname'],
            'birthday' => $birthday,
            'language' => [
                'remote_id' => $person['languageId'],
                'code' => $person['languageCode'],
                'name' => $person['language'],
            ],
            'timezone' => $person['timeZone'],
            'http_env' => $person['http_env'],
            'db_env' => $person['db_env'],
            'schools' => $schools->toArray(),
            'active_school' => $this->getActiveSchool($schools),
        ];

        if (!empty($person['email'])) {
            $parsed['email'] = trim($person['email'], '-');
        }

        $parsed['meta'] = $data;

        return $parsed;
    }

    /**
     * Parse the school key data from response.
     *
     * @param array $schools
     * @return array
     */
    protected function parseWebserviceSchools(array $schools)
    {
        $data = [];
        foreach ($schools as $nodes) {
            foreach ($nodes as $node) {
                foreach ($node as $school) {
                    $parsed = [
                        'remote_id' => $school['schoolId'],
                        'code' => $school['schoolCode'],
                        'name' => $school['school'],
                        'country' => [
                            'remote_id' => $school['countryId'],
                            'code' => $school['countryCode'],
                            'name' => $school['country'],
                        ],
                        'language' => [
                            'remote_id' => $school['languageId'],
                            'code' => $school['languageCode'],
                            'name' => $school['language'],
                        ],
                        'timezone' => $school['timeZone'],
                        'periods' => $this->parseWebservicePeriods($school['schoolPeriods']),
                    ];
                    $data[] = $parsed;
                }
            }
        }
        return $data;
    }

    /**
     * Parse the period key data from response.
     *
     * @param array $periods
     * @return array
     */
    protected function parseWebservicePeriods(array $periods)
    {
        $data = [];
        foreach ($periods as $nodes) {
            foreach ($nodes as $node) {
                foreach ($node as $period) {
                    $parsed = [
                        'period_id' => $period['schoolPeriodId'],
                        'period_code' => $period['schoolPeriodCode'],
                        'period_name' => $period['schoolPeriod'],
                        'period_active' => $period['schoolPeriodActive'],
                    ];
                    $levels = $this->parseWebserviceLevels($period['schoolLevels']);
                    foreach ($levels as $level) {
                        $data[] = array_merge($parsed, $level);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Parse the level key data from response.
     *
     * @param array $levels
     * @return array
     */
    protected function parseWebserviceLevels(array $levels)
    {
        $data = [];
        foreach ($levels as $nodes) {
            foreach ($nodes as $node) {
                foreach ($node as $level) {
                    $parsed = [
                        'level_id' => $level['schoolLevelId'],
                        'level_code' => $level['schoolLevelCode'],
                        'level_name' => $level['schoolLevel'],
                        'level_company_id' => $level['schoolLevelCompanyId'],
                        'level_cycle_id' => $level['colegio_nivel_ciclo_id'],
                    ];
                    $profiles = $this->parseWebserviceProfiles($level['profiles']);
                    foreach ($profiles as $profile) {
                        $data[] = array_merge($parsed, $profile);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Parse the profile key data from response.
     *
     * @param array $profiles
     * @return array
     */
    protected function parseWebserviceProfiles(array $profiles)
    {
        $data = [];
        foreach ($profiles as $nodes) {
            foreach ($nodes as $node) {
                foreach ($node as $profile) {
                    $parsed = [
                        'profile_id' => $profile['profileId'],
                        'profile_code' => $profile['profileCode'],
                        'profile_name' => $profile['profile'],
                    ];
                    if (array_key_exists('grades', $profile)) {
                        $grades = $this->parseWebserviceGrades($profile['grades']);
                        foreach ($grades as $grade) {
                            $data[] = array_merge($parsed, $grade);
                        }
                    } else {
                        $data[] = $parsed;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Parse the grade key data from response.
     *
     * @param array $grades
     * @return array
     */
    protected function parseWebserviceGrades(array $grades)
    {
        $data = [];
        foreach ($grades as $nodes) {
            foreach ($nodes as $node) {
                foreach ($node as $grade) {
                    $data[] = [
                        'grade_id' => $grade['gradeId'],
                        'grade_code' => $grade['gradeCode'],
                        'grade_name' => $grade['grade'],
                    ];
                }
            }
        }
        return $data;
    }

    /**
     * Get active school.
     *
     * @param Collection $schools
     * @return mixed
     */
    protected function getActiveSchool(Collection $schools)
    {
        $period = null;

        $active = $schools->first(function ($item) use (&$period) {
            return collect($item['periods'] ?? [])->first(function ($p) use (&$period) {
                if (array_key_exists('period_active', $p) && $p['period_active']) {
                    $period = $p;
                    return true;
                }
                return false;
            });
        });

        if (empty($active) || empty($period)) {
            return null;
        }

        return array_merge($active, ['active_period' => $period]);
    }
}
