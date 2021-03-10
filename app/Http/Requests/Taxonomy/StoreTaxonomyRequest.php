<?php

namespace Apithy\Http\Requests\Taxonomy;

use Apithy\Services\TaxonomyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaxonomyRequest extends FormRequest
{

    private $taxonomy_service;

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->taxonomy_service = new TaxonomyService($this);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
            'required',
            'max: 191',
            Rule::unique('taxonomy', 'title')
                ->where(function ($query) {
                    return $query->where([
                        ['type', $this->input('type')],
                        ['company_id', $this->taxonomy_service->getAuthCompanyId()]
                    ]);
                })
            ],
            'type' => "required|string|in:{$this->taxonomy_service->type(true)}"
        ];
    }
}
