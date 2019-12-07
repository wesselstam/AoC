<?php
include "../vendor/autoload.php";
$map = file_get_contents('./Map');
$orbits = explode("\n", $map);

$orbitRelations = [];

foreach ($orbits as $orbit) {
    list($a, $b) = explode(')', $orbit);

    $orbitRelations[$b]['orbits'][] = $a;
}

// Walk through app the orbits
function travelOrbits($start, &$orbitRelations)
{
    $path = [];

    if (array_key_exists($start, $orbitRelations) && array_key_exists('orbits', $orbitRelations[$start])) {
        foreach ($orbitRelations[$start]['orbits'] as $suborbit) {
            $path[] = $suborbit;

            if ($subPath = travelOrbits($suborbit, $orbitRelations)) {
                $path = array_merge($path, $subPath);
            }
        }

        return $path;
    }

    return false;
}

$sanOrbits = travelOrbits('SAN', $orbitRelations);
$youOrbits = travelOrbits('YOU', $orbitRelations);

// Find equals and count the number of total orbits
foreach ($sanOrbits as $sanX => $sanOrbit) {
    foreach ($youOrbits as $youX => $youOrbit) {
        if ($sanOrbit == $youOrbit) {
            dump($sanOrbit, $sanX + $youX);
        }
    }
}