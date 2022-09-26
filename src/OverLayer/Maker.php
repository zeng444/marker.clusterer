<?php

declare(strict_types=1);

namespace Janfish\MarkerClusterer\OverLayer;

/**
 * Author:Robert
 *
 * Class Maker
 * @package Janfish\MarkerClusterer
 */
class Maker extends OverLayer
{
    /**
     * @var bool
     */
    private $isInCluster = false;


    /**
     * Author:Robert
     *
     * @return bool
     */
    public function isInCluster(): bool
    {
        return $this->isInCluster;
    }

    /**
     * @return void
     */
    public function setCluster()
    {
        $this->isInCluster = true;
    }
}
