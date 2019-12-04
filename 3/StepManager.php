<?php

/**
 * @author Wessel Stam <wessel@blendis.nl>
 */
class StepManager
{
    const VISITED_LOCATION_INDEX_FORMAT = '%d^%d';
    const OPERATOR_X = ['L' => -1, 'R' => 1, 'U' => 0, 'D' => 0];
    const OPERATOR_Y = ['L' => 0, 'R' => 0, 'U' => 1, 'D' => -1];

    private $intersections;
    private $startPoint;
    private $currentPointer;
    private $instructions;

    public function __construct(array $instructions, array $intersections)
    {
        $this->intersections = $intersections;
        $this->startPoint = [0, 0];
        $this->currentPointer = [0, 0];
        $this->instructions = $instructions;
    }

    private function runInstruction($instruction)
    {
        $this->currentPointer = $this->startPoint;
        $visitedLocations = [];
        $length = 0;

        $instructionSteps = explode(',', $instruction);

        foreach ($instructionSteps as $instructionStep) {
            $direction = $instructionStep{0};
            $steps = substr($instructionStep, 1);

            for ($step = 0; $step < $steps; $step++) {
                list($currentX, $currentY) = $this->currentPointer;

                $newX = $currentX + self::OPERATOR_X[$direction];
                $newY = $currentY + self::OPERATOR_Y[$direction];
                $length++;
                $this->currentPointer = [$newX, $newY];
                $locationIndex = sprintf(self::VISITED_LOCATION_INDEX_FORMAT, $newX, $newY);

                if (!array_key_exists($locationIndex, $visitedLocations)) {
                    $visitedLocations[$locationIndex] = $length;
                }
            }
        }

        return $visitedLocations;
    }

    public function getIntersectionSteps()
    {
        $steps = [];
        foreach ($this->instructions as $wire => $instruction) {
            $steps[$wire] = $this->runInstruction($instruction);
        }

        $combined = [];

        foreach ($steps[0] as $key0 => $step0) {
            if (array_key_exists($key0, $steps[1])) {
                if (array_key_exists($key0, $combined)) {
                    $combined[$key0] += $step0;
                } else {
                    $combined[$key0] = $step0;
                }
            }
        }
        foreach ($steps[1] as $key1 => $step1) {
            if (array_key_exists($key1, $steps[0])) {
                if (array_key_exists($key1, $combined)) {
                    $combined[$key1] += $step1;
                } else {
                    $combined[$key1] = $step1;
                }
            }
        }

        return min($combined);
    }
}