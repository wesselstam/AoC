<?php
include "../vendor/autoload.php";
$passwordLength = 6;

function testPassword($password): bool
{
    if (strlen($password) != 6) {
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
        if (strlen($match) == 2) {
            $foundPairOfTwo = true;
        }
    }
    if (!$foundPairOfTwo) {
        return false;
    }

    $lastChar = false;
    $passwordLength = strlen($password);

    for ($x = 0; $x < $passwordLength; $x++) {
        if (!$lastChar) {
            $lastChar = $password{$x};
            continue;
        }

        if ($password{$x} < $lastChar) {
            return false;
        }

        $lastChar = $password{$x};
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