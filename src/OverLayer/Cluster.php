<?php

declare(strict_types=1);

namespace Janfish\MarkerClusterer\OverLayer;

use Janfish\MarkerClusterer\Util\Helper;

/**
 * Author:Robert
 *
 * Class Cluster
 * @package Janfish\MarkerClusterer\OverLayer
 */
class Cluster extends OverLayer
{
    /**
     * @var  Maker[]
     */
    private $maker = [];

    protected $gridSize;

    protected $markerCount = 0;

    /**
     * @param float $lng
     * @param float $lat
     * @param array $extend
     * @param int $gridSize
     */
    public function __construct(float $lng, float $lat, array $extend, int $gridSize)
    {
        parent::__construct($lng, $lat, []);
        $this->addMaker(new Maker($lng, $lat, $extend));
        $this->gridSize = $gridSize;
    }

    /**
     * @return Maker
     */
    public function getCenter(): Maker
    {
        $lat = 0;
        $lng = 0;
        foreach ($this->maker as $maker) {
            $lat += $maker->getLat();
            $lng += $maker->getLng();
        }
        return new Maker(round($lng / $this->markerCount, 6), round($lat / $this->markerCount, 6), []);
    }

    /**
     * @param Maker $maker
     * @param bool $countOnly
     * @return void
     */
    public function addMaker(Maker $maker, bool $countOnly = false)
    {
        if (!$countOnly) {
            $this->maker[] = $maker;
        }
        ++$this->markerCount;
    }


    /**
     * @return int
     */
    public function getMakerCount(): int
    {
        return $this->markerCount;
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
        $center = $this->getCenter();
        $distance = Helper::getDistance($center->getPosition(), $maker->getPosition());
        return $distance <= sqrt(bcpow((string)$this->gridSize, '2') * 2);
//        [x1, y1, x2, y2]  //其中 (x1, y1) 为左下角的坐标，(x2, y2) 是右上角的坐标
//        (x4-x1)*(x3-x2) < 0 且 (y4-y1)*(y3-y2) < 0
    }

    /**
     * @return Maker[]
     */
    public function getMakers(): array
    {
        return $this->maker;
    }
}
