<?php
include "../vendor/autoload.php";
include "GridManager.php";
include "StepManager.php";
ini_set('MEMORY_LIMIT', '1G');

$instructions = explode("\n", file_get_contents('3.in'));

$gridManager = new GridManager($instructions);
$gridManager->run();
$intersections = $gridManager->getIntersections();

$stepsManager = new StepManager($instructions, $intersections);
dd($stepsManager->getIntersectionSteps($intersections));

// Niet 37364
// Niet 58822