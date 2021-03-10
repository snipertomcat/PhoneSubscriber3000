<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Experience
 * @package Apithy\Utilities\Model\Swagger
 *
 * @SWG\Definition(
 *     required={"type","title","summary","description","visibility"},
 *     @SWG\Xml(name="ExperienceSessions")
 * )
 */
abstract class ExperienceSessions
{
    /**
     * @SWG\Property(type="String",example="Titulo1")
     * @var
     */
    protected $title;

    /**
     * @SWG\Property(type="String",example="Summary")
     * @var
     */
    protected $summary;

    /**
     * @SWG\Property(type="String",example="Description1")
     * @var
     */
    protected $description;

    protected $resource_url;

    protected $code;

    protected $weight;

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
     * @SWG\Property(type="String",example="Author1")
     * @var
     */
    protected $author;

    /**
     * @SWG\Property(type="Integer",example="20")
     * @var
     */
    protected $partner;

    protected $successor_experience;

    protected $user_visibility_settings;

    protected $company_visivility_settings;
}
