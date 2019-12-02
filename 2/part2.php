<?php
include "../vendor/autoload.php";
$intCode = file_get_contents('./intcode');
$baseProgram = explode(',', $intCode);

function getInstruction(array &$program, int $opcodePos)
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

function runProgram(array &$program)
{
    $instructionPointer = 0;

    while ($instruction = getInstruction($program, $instructionPointer)) {
        executeInstruction($instruction, $program);
        $instructionPointer += 4;
    }

    return $program[0];
}

$nouns = range(0,99);
$verbs = range(0,99);

foreach ($nouns as $noun) {
    foreach ($verbs as $verb) {
        $currentProgram = $baseProgram;
        $currentProgram[1] = $noun;
        $currentProgram[2] = $verb;

        if (runProgram($currentProgram) == 19690720) {
            dd("voila", $noun, $verb);
        }
    }
}




