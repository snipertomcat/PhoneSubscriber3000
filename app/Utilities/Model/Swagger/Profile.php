<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Profile
 * @package Apithy
 */

/**
 * @SWG\Definition(
 *     required={"name"},
 *     @SWG\Xml(name="Profile")
 * )
 */
abstract class Profile
{
    /**
     * @SWG\Property(example="Firstname", property="name", type="string")
     * @var String
     */
    public $name;

    /**
     * @SWG\Property(example="Lastname", property="surname", type="String")
     * @var String
     */
    public $surname;

    /**
     * @SWG\Property(example="0123456789", property="phone", type="Integer")
     * @var Integer
     */
    public $phone;

    /**
     * @SWG\Property(example="CompanyName", property="company_name", type="String")
     * @var String
     */
    public $company_name;

    /**
     * @SWG\Property(example="0", property="company_id", type="Integer")
     * @var Integer
     */
    public $company_id;

    /**
     * @SWG\Property(example="0", property="area_id", type="Integer")
     * @var Integer
     */
    public $area_id;

    /**
     * @SWG\Property(example="0", property="position_id", type="Integer")
     * @var Integer
     */
    public $position_id;
}
