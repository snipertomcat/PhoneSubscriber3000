<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Area
 * @package Apithy\Utilities\Model\Swagger
 */

/**
 * @SWG\Definition(
 *     required={
 *         "company_id","name", "short_name"
 *      },
 *     @SWG\Xml(name="Area")
 * )
 */
abstract class Area
{
    /**
     * @SWG\Property(example="1", type="Integer", description="The company's id (duplicated, to revision)")
     * @var Integer
     */
    public $company_id;

    /**
     * @SWG\Property(example="-1", type="Integer")
     * @var Integer
     */
    public $parent_id;

    /**
     * @SWG\Property(example="Company Area 1", type="String")
     * @var String
     */
    public $name;

    /**
     * @SWG\Property(example="Area1", type="String")
     * @var String
     */
    public $short_name;

    /**
     * @SWG\Property(example="This area do something", type="String")
     * @var String
     */
    public $description;
}
