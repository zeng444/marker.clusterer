<?php

declare(strict_types=1);

namespace Janfish\MarkerClusterer\Util;

/**
 * Author:Robert
 *
 * Class Helper
 * @package Janfish\MarkerClusterer
 */
class Helper
{
    public const PI = 3.1415926535897932384626;

    public const EE = 0.00669342162296594323;

    public const EARTH_RADIUS = 6378245.0;


    public static function outOfChina(float $lng, float $lat): bool
    {
        // 纬度3.86~53.55,经度73.66~135.05
        return !($lng > 73.66 && $lng < 135.05 && $lat > 3.86 && $lat < 53.55);
    }


    public static function transformLat(float $lng, float $lat): float
    {
        $ret = -100.0 + 2.0 * $lng + 3.0 * $lat + 0.2 * $lat * $lat + 0.1 * $lng * $lat + 0.2 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lat * self::PI) + 40.0 * sin($lat / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (160.0 * sin($lat / 12.0 * self::PI) + 320 * sin($lat * self::PI / 30.0)) * 2.0 / 3.0;
        return $ret;
    }

    public static function transformLng(float $lng, float $lat): float
    {
        $ret = 300.0 + $lng + 2.0 * $lat + 0.1 * $lng * $lng + 0.1 * $lng * $lat + 0.1 * sqrt(abs($lng));
        $ret += (20.0 * sin(6.0 * $lng * self::PI) + 20.0 * sin(2.0 * $lng * self::PI)) * 2.0 / 3.0;
        $ret += (20.0 * sin($lng * self::PI) + 40.0 * sin($lng / 3.0 * self::PI)) * 2.0 / 3.0;
        $ret += (150.0 * sin($lng / 12.0 * self::PI) + 300.0 * sin($lng / 30.0 * self::PI)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * @param float $lng
     * @param float $lat
     * @return float[]|int[]
     */
    public static function wgs84togcj02(float $lng, float $lat): array
    {
        $dlat = self::transformLat($lng - 105.0, $lat - 35.0);
        $dlng = self::transformLng($lng - 105.0, $lat - 35.0);
        $radlat = $lat / 180.0 * self::PI;
        $magic = sin($radlat);
        $magic = 1 - self::EE * $magic * $magic;
        $sqrtmagic = sqrt($magic);
        $dlat = ($dlat * 180.0) / ((self::EARTH_RADIUS * (1 - self::EE)) / ($magic * $sqrtmagic) * self::PI);
        $dlng = ($dlng * 180.0) / (self::EARTH_RADIUS / $sqrtmagic * cos($radlat) * self::PI);
        $mglat = $lat + $dlat;
        $mglng = $lng + $dlng;
        return [$mglng, $mglat];
    }


    /**
     * @param string $locationA
     * @param string $locationB
     * @return float
     */
    public static function getDistanceByGoogle(string $locationA, string $locationB): float
    {
        list($lng1, $lat1) = explode(',', $locationA);
        list($lng2, $lat2) = explode(',', $locationB);
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        //结果
        $s = acos(cos($radLat1) * cos($radLat2) * cos($radLng1 - $radLng2) + sin($radLat1) * sin($radLat2)) * self::EARTH_RADIUS;
        //精度
        $s = round($s * 10000) / 10000;
        return round($s, 2);
    }


    /**
     * 计算亮点距离取自高德ANDROID SDK
     * @param string $locationA
     * @param string $locationB
     * @return float
     */
    public static function getDistance(string $locationA, string $locationB): float
    {
        list($lng1, $lat1) = explode(',', $locationA);
        list($lng2, $lat2) = explode(',', $locationB);
        $pi = 0.01745329251994329;
        $var4 = $lng1 * $pi;
        $var6 = $lat1 * $pi;
        $var8 = $lng2 * $pi;
        $var10 = $lat2 * $pi;
        $var12 = sin($var4);
        $var14 = sin($var6);
        $var16 = cos($var4);
        $var18 = cos($var6);
        $var20 = sin($var8);
        $var22 = sin($var10);
        $var24 = cos($var8);
        $var26 = cos($var10);
        $var28 = [];
        $var29 = [];
        $var28[0] = $var18 * $var16;
        $var28[1] = $var18 * $var12;
        $var28[2] = $var14;
        $var29[0] = $var26 * $var24;
        $var29[1] = $var26 * $var20;
        $var29[2] = $var22;
        $var30 = sqrt(($var28[0] - $var29[0]) * ($var28[0] - $var29[0]) + ($var28[1] - $var29[1]) * ($var28[1] - $var29[1]) + ($var28[2] - $var29[2]) * ($var28[2] - $var29[2]));
        return round(asin($var30 / 2.0) * 1.27420015798544E7, 2);
    }
}