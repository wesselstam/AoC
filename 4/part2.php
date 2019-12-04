<?php
include "../vendor/autoload.php";
$passwordLength = 6;

function testPassword($password): bool
{
    // Obvious, but true...
    if (strlen($password) !== 6) {
        return false;
    }

    preg_match_all('/(\d)\1+/m', $password, $numberGroups);

    // Check for matches
    if (!count($numberGroups[0])) {
        return false;
    }

    // One of the matches needs to be a pair of 2
    $foundPairOfTwo = false;
    foreach ($numberGroups[0] as $match) {
        if (strlen($match) === 2) {
            $foundPairOfTwo = true;
        }
    }
    if (!$foundPairOfTwo) {
        return false;
    }

    $passwordLength = strlen($password);

    for ($x = 0; $x < $passwordLength; $x++) {
        if ($x > 0 && $password{$x} < $password{$x-1}) {
            return false;
        }
    }

    return true;
}

$min = 273025;
$max = 767253;
$validPasswords = 0;

foreach (range($min, $max) as $number) {
    $password = sprintf('%0'.$passwordLength.'d', $number);

    if (testPassword($password)) {
        $validPasswords++;
    }
}

dd($validPasswords);