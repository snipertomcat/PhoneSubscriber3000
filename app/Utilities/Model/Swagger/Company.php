<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Company
 * @package Apithy\Utilities\Model\Swagger
 */

/**
 * @SWG\Definition(
 *     required={
 *         "country_id", "name", "short_name", "legal_name",
 *         "account_name", "site_url", "contact_email", "contact_phone"
 *     },
 *     @SWG\Xml(name="Company")
 * )
 */
abstract class Company
{
    /**
     * @SWG\Property(example="10", type="Integer")
     * @var Integer
     */
    public $country_id;

    /**
     * @SWG\Property(example="Company name", type="String")
     * @var String
     */
    public $name;

    /**
     * @SWG\Property(example="Company short name", type="String")
     * @var String
     */
    public $short_name;

    /**
     * @SWG\Property(example="Company name A.S.", type="String")
     * @var String
     */
    public $legal_name;

    /**
     * @SWG\Property(example="Administrator", type="String")
     * @var String
     */
    public $account_name;

    /**
     * @SWG\Property(example="https://www.company.com", type="String")
     * @var String
     */
    public $site_url;

    /**
     * @SWG\Property(example="contact@company.com", type="String")
     * @var String
     */
    public $contact_email;

    /**
     * @SWG\Property(example="123456789", type="Integer")
     * @var Integer
     */
    public $contact_phone;
}
