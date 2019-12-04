<?php
include "../vendor/autoload.php";
include "GridManager.php";

$gridManager = new GridManager([
    'R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51',
    'U98,R91,D20,R16,D67,R40,U7,R15,U6,R7'
]);
$gridManager->run();
dd($gridManager->getLowestDistanceIntersection());
