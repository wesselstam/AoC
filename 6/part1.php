<?php
include "../vendor/autoload.php";
$map = file_get_contents('./Map');
$orbits = explode("\n", $map);

$orbitCount = [];

foreach ($orbits as $orbit) {
    list($a, $b) = explode(')', $orbit);

    $orbitCount[$b]['orbits'][] = $a;
}

function countOrbits($letter, &$orbitCount) {
    $totalCount = 0;
    if (array_key_exists($letter, $orbitCount)) {
        $totalCount += count($orbitCount[$letter]['orbits']);

        foreach ($orbitCount[$letter]['orbits'] as $suborbit) {
            if ($suborbitCount = countOrbits($suborbit, $orbitCount)) {
                $totalCount += $suborbitCount;
            }
        }

        return $totalCount;
    }

    return false;
}
$grandTotal = 0;
foreach ($orbitCount as $letter => $vals) {
    if ($letterTotal = countOrbits($letter, $orbitCount)) {
        $grandTotal += $letterTotal;
    }
}

dd($grandTotal);
