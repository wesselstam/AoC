<?php
include "../vendor/autoload.php";
$passwordLength = 6;

function testPassword($password): bool
{
    if (strlen($password) != 6) {
        return false;
    }

    if (!preg_match('/(.)\1{1}/m', $password)) {
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

// Lower than 21480

dd($validPasswords);