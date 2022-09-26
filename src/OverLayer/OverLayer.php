<?php

declare(strict_types=1);

namespace Janfish\MarkerClusterer\OverLayer;

/**
 * Author:Robert
 *
 * Class Position
 * @package Janfish\MarkerClusterer\OverLayer
 */
abstract class OverLayer
{
    /**
     * @var float
     */
    protected $lat;

    /**
     * @var float
     */
    protected $lng;

    /**
     * @var
     */
    protected $extend;

    /**
     * @param float $lng
     * @param float $lat
     * @param array $extend
     */
    public function __construct(float $lng, float $lat, array $extend)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->extend = $extend;
    }

    /**
     * Author:Robert
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->lng . ',' . $this->lat;
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
     * @return array
     */
    public function getExtend(): array
    {
        return $this->extend;
    }
}