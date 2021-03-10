<?php

namespace Apithy\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class BaseResponse implements Responsable
{
    public function toResponse($request)
    {
        if (request()->wantsJson()) {
            return $this->toJsonResponse($request);
        }

        return $this->toHtmlResponse($request);
    }
}
