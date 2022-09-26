<?php

declare(strict_types=1);

namespace Janfish\MarkerClusterer;

use Janfish\MarkerClusterer\Util\Helper;
use Janfish\MarkerClusterer\OverLayer\Cluster;
use Janfish\MarkerClusterer\OverLayer\Maker;

/**
 * Author:Robert
 *
 * Class MarkerClusterer
 * @package Janfish\MarkerClusterer
 */
class MarkerClusterer
{
    /**
     * 聚合算法的可聚合距离(maxZoom    最大18 最小4)
     * @var int
     */
    private $gridSize = 100;

    /**
     * 聚合点的落脚位置是否是所有聚合在内点的平均值，默认为否，落脚在聚合内的第一个点
     * @var bool
     */
    private $averageCenter = false;

    /**
     * 只分组，不记录聚合中的点
     * @var bool|mixed
     */
    private $countOnly = false;

    /**
     * @var Maker[]
     */
    private $maker = [];

    /**
     * @var Cluster[]
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
        if (isset($option['countOnly'])) {
            $this->countOnly = $option['countOnly'];
        }
    }

    /**
     * Author:Robert
     *
     * @param float $lng
     * @param float $lat
     * @param array $extend
     */
    public function addMaker(float $lng, float $lat, array $extend = [])
    {
        $maker = new Maker($lng, $lat, $extend);
        if (!$maker->isInCluster()) {
            $this->addToClosestCluster($maker);
        }
        !$this->countOnly && $this->maker[] = $maker;
    }

    /**
     * Author:Robert
     *
     * @param array $positions
     */
    public function addMakers(array $positions)
    {
        foreach ($positions as $position) {
            $this->addMaker($position[0], $position[1], $position[2] ?? []);
        }
    }

    /**
     * @return Maker[]
     */
    public function getMakers(): array
    {
        return $this->maker;
    }


    /**
     * @return iterable
     */
    public function getClusters(): iterable
    {
        $index = 0;
        foreach ($this->cluster as $cluster) {
            if (sizeof($cluster->getMakers()) < $this->minimumClusterSize) {
                foreach ($cluster->getMakers() as $maker) {
                    yield $index => new Cluster($maker->getLng(), $maker->getLat(), [], $this->gridSize);
                    ++$index;
                }
            } else {
                yield $index => $cluster;
                ++$index;
            }
        }
    }

    /**
     * @param Maker $maker
     */
    private function addToClosestCluster(Maker $maker)
    {
        $currentCluster = null;
        $distance = 4000000;
        foreach ($this->cluster as $cluster) {
            $d = Helper::getDistance($cluster->getCenter()->getPosition(), $maker->getPosition());
            if ($d < $distance) {
                $distance = $d;
                $currentCluster = $cluster;
            }
        }
        if ($currentCluster && $currentCluster->inGrid($maker)) {
            $currentCluster->addMaker($maker, $this->countOnly);
        } else {
            $this->createCluster($maker);
        }
        $maker->setCluster();
    }

    /**
     * @param Maker $maker
     * @return Cluster
     */
    private function createCluster(Maker $maker): Cluster
    {
        $currentCluster = new Cluster($maker->getLng(), $maker->getLat(), $maker->getExtend(), $this->gridSize);
        $this->cluster[] = $currentCluster;
        return $currentCluster;
    }


    /**
     * @return array
     */
    public function getResult(): array
    {
        $result = [];
        foreach ($this->getClusters() as $cluster) {
            $center = $this->averageCenter ? $cluster->getCenter() : current($cluster->getMakers());
            $item['center'] = $center->getPosition();
            $positions = $cluster->getMakers();
            $item['positions'] = [];
            foreach ($positions as $position) {
                $item['positions'][] = $position->getPosition();
            }
            $result[] = $item;
        }
        return $result;
    }
}
