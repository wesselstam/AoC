<?php
include "../vendor/autoload.php";
include "Intcode.php";
$intCode = file_get_contents('./intcode');
//$intCode = '1101,100,-1,4,0';
$program = explode(',', $intCode);

$intCode = new Intcode($program, 1);
$intCode->runProgram();
