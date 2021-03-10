<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Experience
 * @package Apithy\Utilities\Model\Swagger
 *
 * @SWG\Definition(
 *     required={"title","summary","description","company_objectives","area_objectives","visibility"},
 *     @SWG\Xml(name="Experience")
 * )
 */
abstract class Experience
{
    /**
     * @SWG\Property(type="String",example="adventure")
     * @var
     */
    protected $type;

    /**
     * @SWG\Property(type="String",example="Experience #")
     * @var
     */
    protected $title;

    /**
     * @SWG\Property(type="String",example="<p>Summary of experience</p>")
     * @var
     */
    protected $summary;

    /**
     * @SWG\Property(type="String",example="<p>Description of experience</p>")
     * @var
     */
    protected $description;

    protected $code;

    /**
     * @SWG\Property(type="String",example="<p>Company objetive</p>")
     * @var
     */
    protected $company_objectives;

    /**
     * @SWG\Property(type="String",example="<p>Area objetive</p>")
     * @var
     */
    protected $area_objectives;

    /**
     * @SWG\Property(type="Integer",example="10")
     * @var
     */
    protected $points_default;

    /**
     * @SWG\Property(type="Integer",example="10")
     * @var
     */
    protected $duration_limit_default;

    /**
     * @SWG\Property(type="Integer",example="5")
     * @var
     */
    protected $level_default;

    /**
     * @SWG\Property(type="Integer",example="1")
     * @var
     */
    protected $visibility;

    /**
     * @SWG\Property(type="String",example="on")
     * @var
     */
    protected $status;

    /**
     * @SWG\Property(type="String",example="2018-06-01")
     * @var
     */
    protected $available_from;

    /**
     * @SWG\Property(type="String",example="2018-06-30")
     * @var
     */
    protected $available_to;

    /**
     * @SWG\Property(type="Integer",example="1")
     * @var
     */
    protected $author;

    /**
     * @SWG\Property(type="Integer",example="1")
     * @var
     */
    protected $partner;

    protected $cover;

    protected $user_visibility_settings;

    protected $company_visibility_settings;

    protected $company_id;
}
