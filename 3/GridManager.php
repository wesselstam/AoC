<?php

/**
 * @author Wessel Stam <wessel@blendis.nl>
 */
class GridManager
{
    const COMMAND_UP = 'U';
    const COMMAND_DOWN = 'D';
    const COMMAND_LEFT = 'L';
    const COMMAND_RIGHT = 'R';
    const VISITED_LOCATION_INDEX_FORMAT = '%d^%d';

    private $visitedLocations;
    private $startPoint;
    private $currentPointer;
    private $instructions;
    private $currentWire;

    public function __construct(array $instructions)
    {
        $this->visitedLocations = [];
        $this->startPoint = [0, 0];
        $this->currentPointer = [0, 0];
        $this->instructions = $instructions;
    }

    public function run(): void
    {
        foreach ($this->instructions as $wireNumber => $instruction) {
            $this->currentPointer = $this->startPoint;
            $this->currentWire = $wireNumber;
            $this->visitedLocations[$wireNumber] = [];

            $instructionSteps = explode(',', $instruction);

            foreach ($instructionSteps as $instructionStep) {
                $direction = $instructionStep{0};
                $steps = substr($instructionStep, 1);

                $this->movePointer($direction, $steps);
            }
        }
    }

    private function movePointer(string $direction, int $steps): void
    {
        for ($step = 0; $step < $steps; $step++) {
            list($currentX, $currentY) = $this->currentPointer;

            switch ($direction) {
                case self::COMMAND_UP:
                    $this->currentPointer = [$currentX, $currentY+1];
                    break;
                case self::COMMAND_DOWN:
                    $this->currentPointer = [$currentX, $currentY-1];
                    break;
                case self::COMMAND_LEFT:
                    $this->currentPointer = [$currentX-1, $currentY];
                    break;
                case self::COMMAND_RIGHT:
                    $this->currentPointer = [$currentX+1, $currentY];
                    break;
            }

            $this->registerLocation();
        }
    }

    private function registerLocation(): void
    {
        list($x, $y) = $this->currentPointer;
        $locationIndex = sprintf(self::VISITED_LOCATION_INDEX_FORMAT, $x, $y);

        if (!array_key_exists($locationIndex, $this->visitedLocations[$this->currentWire])) {
            $this->visitedLocations[$this->currentWire][$locationIndex] = true;
        }
    }

    public function getIntersections(): array
    {
        $intersections = [];
        $locationTotals = [];

        foreach ($this->visitedLocations as $wire => $locations) {
            foreach ($locations as $location => $val) {
                if (!array_key_exists($location, $locationTotals)) {
                    $locationTotals[$location] = 1;
                } else {
                    $locationTotals[$location]++;
                }
            }
        }

        foreach ($locationTotals as $location => $total) {
            if ($total > 1) {
                list($pointerX, $pointerY) = explode('^', $location);
                $intersections[$location] = $this->calculateDistance($pointerX, $pointerY);
            }
        }

        return $intersections;
    }

    public function getLowestDistanceIntersection(): int
    {
        foreach ($this->getIntersections() as $intersection) {
            if (!isset($smallest) || $intersection < $smallest) {
                $smallest = $intersection;
            }
        }

        return $smallest;
    }

    public function getIntersectionSteps(array $intersections)
    {
        $intersectionSteps = [];

        foreach ($intersections as $location => $intersection) {
            list($intersectionX, $intersectionY) = explode('^', $location);

            $intersectionSteps[$location] = $this->countSteps($intersectionX, $intersectionY);
        }

        return $intersectionSteps;
    }

    public function getSmallestIntersectionSteps(array $intersections)
    {
        foreach ($this->getIntersectionSteps($intersections) as $intersectionStep) {
            if (!isset($smallest) || $intersectionStep < $smallest) {
                $smallest = $intersectionStep;
            }
        }

        return $smallest;
    }

    private function countSteps(int $x, int $y)
    {
        $totalSteps = 0;
        foreach ($this->instructions as $wire => $instruction) {
            $this->currentPointer = $this->startPoint;
            $visitedLocations = [];

            $stepCount = 0;
            $instructionSteps = explode(',', $instruction);

            foreach ($instructionSteps as $instructionStep) {
                $direction = $instructionStep{0};
                $steps = substr($instructionStep, 1);

                for ($step = 0; $step < $steps; $step++) {
                    $stepCount++;

                    list($currentX, $currentY) = $this->currentPointer;

                    switch ($direction) {
                        case self::COMMAND_UP:
                            $this->currentPointer = [$currentX, $currentY+1];
                            break;
                        case self::COMMAND_DOWN:
                            $this->currentPointer = [$currentX, $currentY-1];
                            break;
                        case self::COMMAND_LEFT:
                            $this->currentPointer = [$currentX-1, $currentY];
                            break;
                        case self::COMMAND_RIGHT:
                            $this->currentPointer = [$currentX+1, $currentY];
                            break;
                    }

                    if ($this->currentPointer[0] == $x && $this->currentPointer[1] == $y) {
                        $totalSteps = $stepCount;
                        unset($visitedLocations);
                        dump('break wire ' . $wire, $x, $y);
                        break 2;
                    }

                    $locationIndex = sprintf(self::VISITED_LOCATION_INDEX_FORMAT, $this->currentPointer[0], $this->currentPointer[1]);
                    if (!array_key_exists($locationIndex, $visitedLocations)) {
                        $visitedLocations[$locationIndex] = $stepCount;
                    } else {
                        dump('overwrite');
                        $stepCount = $visitedLocations[$locationIndex];
                    }
                }
            }


        }

        return $totalSteps;
    }

    public function calculateDistance(int $x, int $y): int
    {
        $vector1 = [$x, $y];
        $vector2 = $this->startPoint;

        $n = count($vector1);
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum += abs($vector1[$i] - $vector2[$i]);
        }
        return $sum;
    }
}