<?php
include "../vendor/autoload.php";
include "Intcode.php";
$intCode = file_get_contents('./intcode');
$program = explode(',', $intCode);

$intCode = new Intcode($program, 5);
dd($intCode->runProgram());
