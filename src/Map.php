<?php
declare(strict_types=1);

namespace Janfish\MarkerCluster;

use Janfish\MarkerCluster\OverLayer\Cluster;
use Janfish\MarkerCluster\OverLayer\Maker;

/**
 * Author:Robert
 *
 * Class Map
 * @package Janfish\MarkerCluster
 */
class Map
{

    /**
     * 聚合算法的可聚合距离(maxZoom    最大18 最小4)
     * @var int
     */
    private $gridSize = 500;

    /**
     * 聚合点的落脚位置是否是所有聚合在内点的平均值，默认为否，落脚在聚合内的第一个点
     * @var bool
     */
    private $averageCenter = false;

    /**
     * @var array
     */
    private $maker = [];

    /**
     * @var array
     */
    private $cluster = [];

    /**
     * 最小的聚合数量，小于该数量的不能成为一个聚合，默认为2
     * @var int
     */
    private $minimumClusterSize = 2;

    /**
     * @param array $option
     */
    public function __construct(array $option = [])
    {
        if (isset($option['gridSize'])) {
            $this->gridSize = $option['gridSize'];
        }
        if (isset($option['averageCenter'])) {
            $this->averageCenter = $option['averageCenter'];
        }
        if (isset($option['minimumClusterSize'])) {
            $this->minimumClusterSize = $option['minimumClusterSize'];
        }
    }

    /**
     * Author:Robert
     *
     * @param float $lng
     * @param float $lat
     */
    public function addMaker(float $lng, float $lat)
    {
        $this->maker[] = new Maker($lng, $lat);
    }

    /**
     * Author:Robert
     *
     * @param array $positions
     */
    public function addMakers(array $positions)
    {
        foreach ($positions as $position) {
            $this->addMaker($position[0], $position[1]);
        }
    }

    /**
     * Author:Robert
     *
     */
    public function init()
    {
        foreach ($this->maker as $maker) {
            if (!$maker->isInCluster()) {
                $this->addToClosestCluster($maker);
            }
        }
    }

    /**
     * Author:Robert
     *
     * @return iterable
     */
    public function getClusters(): iterable
    {
        return $this->cluster;
    }

    /**
     * Author:Robert
     *
     * @param Maker $maker
     */
    private function addToClosestCluster(Maker $maker)
    {
        $currentCluster = null;
        $distance = 4000000;
        /** @var Cluster $cluster */
        foreach ($this->cluster as $cluster) {
            $d = Helper::getDistance($cluster->getCenter(), $maker->getPosition());
            if ($d < $distance) {
                $distance = $d;
                $currentCluster = $cluster;
            }
        }
        if ($currentCluster && $currentCluster->inGrid($maker)) {
            $currentCluster->addMaker($maker);
        } else {
            $currentCluster = $this->createCluster();
            $currentCluster->addMaker($maker);
        }
    }

    /**
     * Author:Robert
     *
     * @return Cluster
     */
    private function createCluster(): Cluster
    {
        $currentCluster = new Cluster(['gridSize' => $this->gridSize]);
        $this->cluster[] = $currentCluster;
        return $currentCluster;
    }


}