<?php

use Apithy\Utilities\Model\Sortable;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\Twilio\TwilioChannel;

if (!function_exists('flash')) {

    /**
     * Flash an message for an alert.
     *
     * @param  string $message
     * @param  string $type
     */
    function flash($message, $type = 'info', $header = '')
    {
        session()->flash('flash_alert_message', $message);
        session()->flash('flash_alert_type', $type);
        session()->flash('flash_alert_header', $header);
    }
}

if (!function_exists('url_is')) {
    /**
     * Checks the current url matches the given.
     *
     * @param  string $url
     * @param  boolean $pos
     * @return boolean
     */
    function url_is($url, $pos = false)
    {
        return $pos
            ? strpos(url()->current(), trim($url)) !== false
            : url()->current() == trim($url);
    }
}

if (!function_exists('v_sort')) {
    /**
     * Heading table sort url anchor.
     *
     * @param string $field
     * @param string $display
     * @return string
     */
    function v_sort($field, $display = null)
    {
        $request = request();
        $currentSort = $request->get('sort');
        $currentDirection = $request->get('direction');

        if ($currentSort == $field) {
            $direction = $currentDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $direction = $currentSort ? 'asc' : 'desc';
        }

        $params = array_merge($request->all(), [
            'sort' => $field,
            'direction' => $direction,
            'page' => $request->get('page', 1),
        ]);

        $icon = ($currentSort == $field)
            ? sprintf('sort-%s', strtolower($direction))
            : 'sort';

        $url = $request->fullUrlWithQuery($params);

        if (is_null($display)) {
            $display = trans(sprintf('messages.%s', $field));
        }

        $template = '<a href="%s" class="button"></span><span>%s</span>';
        $template .= '<span class="icon is-small"><i class="fa fa-%s"></i></a>';

        return sprintf($template, $url, $display, $icon);
    }
}

if (!function_exists('listing')) {
    /**
     * Paginates, sorts and filter the given model.
     *
     * @param string $model
     * @param array $with
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function listing($model, $with = [], $per_page = null)
    {
        $per_page = (isset($per_page)) ? $per_page : config('pagination.size');
        if (is_string($model)) {
            $instance = app($model);
        } else {
            $instance = $model;
        }

        if ($instance instanceof \Illuminate\Database\Eloquent\Model) {
            $query = $instance->query();
        } else {
            $query = $instance;
        }

        if (method_exists($model, 'scopeAllowed')) {
            $query->allowed();
        }

        $sort = request()->get('sort', false);
        $filter = request()->get('filter', false);
        $search = request()->get('search', false);

        // Cleaning filters on equality for simplicity
        if (is_array($filter)) {
            foreach ($filter as $column => $exec) {
                if (is_string($exec) && strlen($exec) <= 0) {
                    unset($filter[$column]);
                }
            }
        }

        if (!empty($search)) {
            $query = $query->search($search);
        }

        if (is_string($sort) && $instance instanceof Sortable) {
            $query = $query->sort($sort, request()->get('direction'));
        }

        if (is_array($filter)) {
            $query = $query->filter($filter);
        }

        if (!empty($with)) {
            $query->with($with);
        }

        if ($per_page === -1) {
            return collect(['data' => $query->get()]);
        }

        return $query->paginate($per_page);
    }
}

if (!function_exists('on_filter')) {
    /**
     * Checks simple filter equality if has the given column and value
     *
     * @param string $column
     * @param mixed $value
     * @return string
     */
    function on_filter($column, $value)
    {
        $filter = request('filter', []);
        return is_array($filter) && array_key_exists($column, $filter) && $filter[$column] == $value;
    }
}

function getBodyClass()
{
    $body_classes = [];
    $class = "";
    foreach (Request::segments() as $segment) {
        if (is_numeric($segment) || empty($segment)) {
            continue;
        }
        $class .= !empty($class) ? "-" . $segment : $segment;
        array_push($body_classes, $class);
    }
    if (empty($body_classes)) {
        $body_classes[] = 'home';
    }
    return !empty($body_classes) ? implode(' ', $body_classes) : null;
}

function getEnvURL()
{
    $env = env('APP_ENV', 'prod');
    $https = env('APP_HTTPS', false);

    if ($env != 'prod') {
        $env = $env . '.';
    } else {
        $env = 'www';
    }

    $protocol = 'http://';
    if ($https) {
        $protocol = 'https://';
    }

    return $protocol . $env . 'apithy.com';
}

function getEnvCompanyURL($user)
{
    $env = env('APP_ENV', 'prod');
    $https = env('APP_HTTPS', false);

    if ($env != 'prod') {
        $env = $env . '.';
    } else {
        $env = '';
    }

    $protocol_part = 'http://';
    $company_part='';

    if ($https) {
        $protocol_part = 'https://';
    }

    if ($user->company()->first()) {
        $company_part=$user->company[0]->account_name.".";
    } else {
        return getEnvURL();
    }

    $url=$protocol_part .$company_part. $env . 'apithy.com';

    return $url;
}

function getSMSService()
{
    $sms_config = config('services.sms');
    return $sms_config;
}


function getSMSChannel()
{
    $sms_config = config('services.sms');

    if ($sms_config == 'twilio') {
        return TwilioChannel::class;
    } elseif ($sms_config == 'sns') {
        return SnsChannel::class;
    }

    return SnsChannel::class;

}
