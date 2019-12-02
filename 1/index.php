<?php
include("../vendor/autoload.php");

$masses = file_get_contents('./masses');

$lines = explode("\n", $masses);

function calculateTotalFuel(int $mass)
{
    $currentFuel = calculateFuel($mass);
    $totalFuel = $currentFuel;

    while (calculateFuel($currentFuel) > 0) {
        $currentFuel = calculateFuel($currentFuel);
        $totalFuel += $currentFuel;
    }

    return $totalFuel;

}

function calculateFuel(int $mass)
{
    return floor($mass / 3) - 2;
}

$fuel = 0;

foreach ($lines as $line) {
    $val = trim($line);
    $fuel += calculateTotalFuel($val);
}

dd($fuel);
