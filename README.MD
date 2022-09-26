### 标记聚合器

> 用来解决加载大量点要素到地图上产生覆盖现象的问题，并提高性能

```php
use Janfish\MarkerClusterer\MarkerClusterer;

$positions = [
    [104.082246, 30.656996, ["extendA" => "a"]],
    [104.082965, 30.656926, ["extendA" => "3"]],
    [104.084757, 30.656857],
    [104.083840, 30.655413],
    [104.082252, 30.654001],
    [104.081254, 30.654061, ["extendA" => "333"]],
    [104.081313, 30.653567],
    [104.082413, 30.649889],
    [104.081603, 30.649796],
    [104.081490, 30.649949],
    [104.076925, 30.641803],
    [104.076925, 30.641803],
    [104.077199, 30.641295],
    [104.076887, 30.641401],
];
$scene = new MarkerClusterer([
    'gridSize' => 100,
    'averageCenter' => true,
    'minimumClusterSize' => 2,
    'countOnly' => false,
]);
$scene->addMakers($positions);
$scene->addMaker(104.076887, 30.641401, ["extra data"]);
foreach ($scene->getClusters() as $cluster) {
    echo 'center position' . $cluster->getCenter()->getPosition() . PHP_EOL;
    foreach ($cluster->getMakers() as $maker) {
        echo $maker->getPosition() . ' ' . json_encode($maker->getExtend()) . PHP_EOL;
    }
    echo PHP_EOL;
}

```