<?php
declare(strict_types=1);

namespace Janfish\MarkerCluster\OverLayer;

/**
 * Author:Robert
 *
 * Class Maker
 * @package Janfish\MarkerCluster
 */
class Maker
{
    /**
     * @var float
     */
    public $lat;

    /**
     * @var float
     */
    public $lng;

    /**
     * @var bool 
     */
    private $isInCluster = false;


    /**
     * @param float $lng
     * @param float $lat
     */
    public function __construct(float $lng, float $lat)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->lng.','.$this->lat;
    }

    /**
     * Author:Robert
     *
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * Author:Robert
     *
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * Author:Robert
     *
     * @return bool
     */
    public function isInCluster(): bool
    {
        return $this->isInCluster;
    }

    public function setCluster(): bool
    {
         $this->isInCluster = true;
    }
}