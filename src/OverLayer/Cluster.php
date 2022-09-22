<?php
declare(strict_types=1);

namespace Janfish\MarkerCluster\OverLayer;

/**
 * Author:Robert
 *
 * Class Cluster
 * @package Janfish\MarkerCluster\OverLayer
 */
class Cluster
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
     * @var
     */
    public $gridSize;

    /**
     * @var array
     */
    private $maker = [];

    /**
     *
     */
    public function __construct(array $options)
    {
        $this->gridSize = $options['gridSize'];
    }


    public function getCenter(): string
    {

    }

    public function addMaker(Maker $maker)
    {
        $this->maker[] = $maker;
    }

    /**
     * 计算传进去的点点矩形是否重叠
     * Author:Robert
     *
     * @param Maker $maker
     * @return bool
     */
    public function inGrid(Maker $maker): bool
    {
        $location = $maker->getPosition();
        $center = $this->getCenter();
        return true;
    }

    /**
     * Author:Robert
     *
     * @return iterable
     */
    public function getMakers(): iterable
    {
        return $this->maker;
    }

}