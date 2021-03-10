<?php

namespace Apithy\Utilities\Model\Swagger;

/**
 * Class Position
 * @package Apithy\Utilities\Model\Swagger
 */

/**
 * @SWG\Definition(
 *     required={"area_id", "name", "short_name"},
 *     @SWG\Xml(name="Position")
 * )
 */
abstract class Position
{
    /**
     * @SWG\Property(example="1", type="Integer", description="The area's id (duplicated, to revision)")
     * @var Integer
     */
    public $area_id;

    /**
     * @SWG\Property(example="-1", type="Integer")
     * @var Integer
     */
    public $parent_id;

    /**
     * @SWG\Property(example="Position 1", type="String")
     * @var String
     */
    public $name;

    /**
     * @SWG\Property(example="Pos1", type="String")
     * @var String
     */
    public $short_name;

    /**
     * @SWG\Property(example="This position do somethings", type="String")
     * @var String
     */
    public $description;
}
