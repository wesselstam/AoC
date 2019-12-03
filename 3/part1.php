<?php
include "../vendor/autoload.php";

class GridManager
{
    const COMMAND_UP = 'U';
    const COMMAND_DOWN = 'D';
    const COMMAND_LEFT = 'L';
    const COMMAND_RIGHT = 'R';

    private $visitedLocations;
    private $startPoint;
    private $currentPointer;
    private $instructions;

    public function __construct(array $instructions)
    {
        $this->visitedLocations = [];
        $this->startPoint = [0, 0];
        $this->currentPointer = [0, 0];
        $this->instructions = $instructions;
    }

    public function run()
    {
        foreach ($this->instructions as $instruction) {
            $this->currentPointer = $this->startPoint;

            $instructionSteps = explode(',', $instruction);

            foreach ($instructionSteps as $instructionStep) {
                $direction = $instructionStep{0};
                $steps = substr($instructionStep, 1);

                $this->movePointer($direction, $steps);
            }
        }
    }

    private function movePointer(string $direction, int $steps)
    {
        for ($step = 0; $step < $steps; $step++) {
            switch ($direction) {
                case self::COMMAND_UP:
                    $this->currentPointer = [$this->currentPointer[0], $this->currentPointer[1]+1];
                    break;
                case self::COMMAND_DOWN:
                    $this->currentPointer = [$this->currentPointer[0], $this->currentPointer[1]-1];
                    break;
                case self::COMMAND_LEFT:
                    $this->currentPointer = [$this->currentPointer[0]-1, $this->currentPointer[1]];
                    break;
                case self::COMMAND_RIGHT:
                    $this->currentPointer = [$this->currentPointer[0]+1, $this->currentPointer[1]];
                    break;
            }

            $this->registerLocation();
        }
    }

    private function registerLocation()
    {
        list($x, $y) = $this->currentPointer;

        if (!array_key_exists($x . '^' . $y, $this->visitedLocations)) {
            $this->visitedLocations[$x . '^' . $y] = 1;
        } else {
            $this->visitedLocations[$x . '^' . $y]++;
        }
    }

    public function getVisitedLocations()
    {
        return $this->visitedLocations;
    }

    public function getIntersections()
    {
        $intersections = [];

        foreach ($this->visitedLocations as $pointer => $visitedLocation) {
            if ($visitedLocation > 1) {
                list($pointerX, $pointerY) = explode('^', $pointer);
                $intersections[$pointer] = $this->calculateDistance($pointerX, $pointerY);
            }
        }

        return $intersections;
    }

    public function getLowestDistanceIntersection()
    {
        foreach ($this->getIntersections() as $intersection) {
            if (!isset($smallest) || $intersection < $smallest) {
                $smallest = $intersection;
            }
        }

        return $smallest;
    }

    public function calculateDistance(int $x, int $y)
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

$gridManager = new GridManager([
    'R98,U47,R26,D63,R33,U87,L62,D20,R33,U53,R51',
    'U98,R91,D20,R16,D67,R40,U7,R15,U6,R7'
]);
$gridManager->run();
dd($gridManager->getLowestDistanceIntersection());
