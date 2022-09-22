<?php
declare(strict_types=1);

namespace Janfish\MarkerCluster;

/**
 * Author:Robert
 *
 * Class Helper
 * @package Janfish\MarkerCluster
 */
class Helper
{

    const PI = 3.1415926535897932384626;

    const EE = 0.00669342162296594323;

    const A = 6378245.0;

    static function out_of_china($lng, $lat)
    {
        // 纬度3.86~53.55,经度73.66~135.05
        return !($lng > 73.66 && $lng < 135.05 && $lat > 3.86 && $lat < 53.55);
    }


    static function transformlat($lng, $lat)
    {
        $ret = -100.0 + 2.0 * $lng + 3.0 * $lat + 0.2 * $lat * $lat + 0.1 * $lng * $lat + 0.2 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lat * self::PI) + 40.0 * sin($lat / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($lat / 12.0 * self::PI) + 320 * sin($lat * self::PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }

    static function transformlng($lng, $lat)
    {
        $ret = 300.0 + $lng + 2.0 * $lat + 0.1 * $lng * $lng + 0.1 * $lng * $lat + 0.1 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lng * self::PI) + 40.0 * sin($lng / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($lng / 12.0 * self::PI) + 300.0 * sin($lng / 30.0 * self::PI)) * 2.0 / 3.0;
        return $ret;
    }

    public static function wgs84togcj02($lng, $lat)
    {

        $dlat = self::transformlat($lng - 105.0, $lat - 35.0);
        $dlng = self::transformlng($lng - 105.0, $lat - 35.0);
        $radlat = $lat / 180.0 * self::PI;
        $magic = sin($radlat);
        $magic = 1 - self::EE * $magic * $magic;
        $sqrtmagic = sqrt($magic);
        $dlat = ($dlat * 180.0) / ((self::A * (1 - self::EE)) / ($magic * $sqrtmagic) * self::PI);
        $dlng = ($dlng * 180.0) / (self::A / $sqrtmagic * cos($radlat) * self::PI);
        $mglat = $lat + $dlat;
        $mglng = $lng + $dlng;
        return [$mglng, $mglat];

    }

    /**
     * Author:Robert
     *
     * @return float
     */
    public static function getDistance(string $locationA,string $locationB): float
    {
        return 0;
    }

}