<?php
include "../vendor/autoload.php";
$intCode = file_get_contents('./intcode');
$program = explode(',', $intCode);

$program[1] = 12;
$program[2] = 2;

function getInstruction(array $program, $opcodePos)
{
    if ($program[$opcodePos] != 99) {
        return array_slice($program, $opcodePos, 4);
    }

    return false;
}

function executeInstruction(array $instruction, array &$program)
{
    $opcode = $instruction[0];
    $parameter1 = $instruction[1];
    $parameter2 = $instruction[2];
    $output = $instruction[3];

    if ($opcode == 1) {
        $program[$output] = $program[$parameter1] + $program[$parameter2];
    } elseif ($opcode == 2) {
        $program[$output] = $program[$parameter1] * $program[$parameter2];
    }
}

$instructionPointer = 0;

while ($instruction = getInstruction($program, $instructionPointer)) {
    executeInstruction($instruction, $program);
    $instructionPointer += 4;
}

dd($program);