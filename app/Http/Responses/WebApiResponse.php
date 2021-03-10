<?php

namespace Apithy\Http\Responses;

use Apithy\Http\Responses\BaseResponse as BaseResponse;
use Apithy\Utilities\Controllers\Helpers;

class WebApiResponse extends BaseResponse
{
    use Helpers;


    public $data;
    public $view;
    public $resource;
    public $responseFor;
    public $responseData;
    public $options;

    const RESPONSE_FOR_UPDATE = 'update';
    const RESPONSE_FOR_STORE = 'store';
    const RESPONSE_FOR_DELETE = 'delete';


    public function __construct($data, $view = false, $responseFor = false, $resource = false, $options = [])
    {
        $this->data = $data;
        $this->view = $view;
        $this->resource = $resource;
        $this->responseFor = $responseFor;
        $this->options=$options;
        $this->setDataForResponse();
    }

    public function setDataForResponse()
    {
        if (is_array($this->data)) {
            $data_index = current(array_keys($this->data));
            $this->responseData = $this->data[$data_index];
        } else {
            $this->responseData = $this->data;
        }
    }

    public function toJsonResponse()
    {
        return $this->responseData;
    }

    public function toHtmlResponse()
    {
        if ($this->view) {
            return view($this->view, $this->data);
        }

        switch ($this->responseFor) {
            case self::RESPONSE_FOR_STORE:
                return $this->responseForStore($this->responseData, $this->resource, $this->options);
                break;
            case self::RESPONSE_FOR_UPDATE:
                return $this->responseForEdit($this->responseData, $this->resource, $this->options);
                break;
            case self::RESPONSE_FOR_DELETE:
                return $this->deleteResourceAndResponse($this->responseData, $this->resource, $this->options);
                break;
        }
    }
}
