<?php

namespace Apithy\Utilities\Controllers;

/**
 * Trait Helpers
 * @package Apithy\Utilities\Controllers
 */
trait Helpers
{
    /**
     * Redirect for success or failed resources on store with flash message.
     *
     * @param mixed $resource
     * @param string $name
     * @param string $prefix
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function responseForStore($resource, $name, $options)
    {

        if ($resource && isset($options['url_redirect'])) {
            if (isset($options['url_redirect_with_params'])) {
                return redirect($options['url_redirect'])
                    ->with(
                        $options['url_redirect_with_params']['key'],
                        $options['url_redirect_with_params']['value']
                    );
            }
            return redirect($options['url_redirect']);
        } else {
            flash(trans(sprintf('messages.%s_failed', $name)), 'danger');
        }

        flash(trans(sprintf('messages.%s_created', $name)), 'success');
        return redirect(sprintf('%s', $name));
    }

    /**
     * Redirect for success or failed resources on edit with flash message.
     *
     * @param mixed $resource
     * @param string $name
     * @param string $prefix
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function responseForEdit($resource, $name, $options)
    {
        if ($resource && !isset($options['url_redirect'])) {
            flash(trans(sprintf('messages.%s_updated', $name)), 'success');
        } else {
            flash(trans(sprintf('messages.%s_failed', $name)), 'danger');
        }

        if (isset($options['url_redirect'])) {
            return redirect($options['url_redirect']);
        }

        $resourceId=$resource->id;
        if (isset($options['use_hash_id'])) {
            $hashids = new \Hashids\Hashids(env('APP_URL_KEY'), 10);
            $resourceId= $hashids->encode($options['use_hash_id']);
        }



        return redirect(sprintf('%s', $name));
    }


    /**
     * Delete resource with the response.
     *
     * @param mixed $resource
     * @param string $name
     * @param string $prefix
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function deleteResourceAndResponse($resource, $name, $options)
    {
        if ($resource) {
            flash(trans(sprintf('messages.%s_deleted', $name)), 'warning');
        }

        if (isset($options['url_redirect'])) {
            return redirect($options['url_redirect']);
        }

        return redirect()->back();
    }

    /**
     * Checks the authenticated user typed it password.
     *
     * @return boolean
     */
    protected function checkAuthPassword()
    {
        $confirmed = request()->user()->confirmPassword(request('password'));

        if (!$confirmed) {
            flash(trans('messages.invalid_current_password'), 'danger');
        }

        return !! $confirmed;
    }
}
