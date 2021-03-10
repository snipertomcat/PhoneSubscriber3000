<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Invitation
 * @package Apithy
 */

/**
 * @SWG\Definition(
 *     required={"company_id", "role_id", "email"},
 *     @SWG\Xml(name="Invitation")
 * )
 */

abstract class Invitation
{
    /**
     * @SWG\Property(example="0", property="company_id", type="Integer")
     * @var Integer
     */
    public $company_id;

    /**
     * @SWG\Property(example="0", property="role_id", type="Integer")
     * @var Integer
     */
    public $role_id;

    /**
     * @SWG\Property(example="test@domain.com", property="email", type="String")
     * @var String
     */
    public $email;
}
