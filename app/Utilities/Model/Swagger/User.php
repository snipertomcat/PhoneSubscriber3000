<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class User
 * @package Apithy
 */

/**
 * @SWG\Definition(
 *     required={"name", "email", "surname", "register_type", "role_id", "activated"},
 *     @SWG\Xml(name="User")
 * )
 */

abstract class User
{

    /**
     * @SWG\Property(example="Firstname", property="name", type="Integer")
     * @var string
     */
    public $name;


    /**
     * @SWG\Property(example="test@apithy.com", property="email", type="String")
     * @var string
     */
    public $email;

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

    /**
     * @SWG\Property(example="admin", property="register_type", type="String")
     * @var String
     */
    public $register_type;

    /**
     * @SWG\Property(example="5", property="role_id", type="Integer")
     * @var Integer
     */
    public $role_id;

    /**
     * @SWG\Property(example="1", property="activated", type="Integer")
     * @var Integer
     */
    public $activated;
}
