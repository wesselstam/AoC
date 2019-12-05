<?php

/**
 * @author Wessel Stam <wessel@blendis.nl>
 */
class Intcode
{
    private $program;

    private $opcodeLength = [1 => 4, 2 => 4, 3 => 2, 4 => 2, 5 => 3, 6 => 3, 7 => 4, 8 => 4];
    private $input;
    private $writtenParameters = [];
    private $pointer;

    public function __construct(array $program, $input = false)
    {
        $this->program = $program;
        $this->input = $input;
        $this->pointer = 0;
    }

    public function runProgram()
    {
        while ($instruction = $this->getInstruction()) {
            $this->executeInstruction($instruction);
        }

        return $this->program;
    }

    private function getInstruction()
    {
        $rawOpcode = (string)$this->program[$this->pointer];

        if (strlen($rawOpcode) > 1) {
            $opcode = (int)$rawOpcode[strlen($rawOpcode)-1];
        } else {
            $opcode = (int)$rawOpcode;
        }

        if (array_key_exists($opcode, $this->opcodeLength) && $opcode != 99) {
            $instruction = array_slice($this->program, $this->pointer, $this->opcodeLength[$opcode]);
            return $instruction;
        }

        return false;
    }

    private function executeInstruction(array $instruction)
    {
        $rawOpcode = (string)$instruction[0];

        if (strlen($rawOpcode) > 1) {
            $opcode = (int)$rawOpcode[strlen($rawOpcode)-1];

            $parameterMode1 = $rawOpcode[strlen($rawOpcode)-3];
            if ($parameterMode1 == 0 || array_key_exists($this->pointer+1, $this->writtenParameters)) {
                $parameter1 = $this->program[$instruction[1]];
            } else {
                $parameter1 = $instruction[1];
            }

            if (array_key_exists(2, $instruction)) {
                $parameterMode2 = ((strlen($rawOpcode) - 4) < 0) ? 0 : $rawOpcode[strlen($rawOpcode) - 4];
                if ($parameterMode2 == 0 || array_key_exists($this->pointer + 2, $this->writtenParameters)) {
                    $parameter2 = $this->program[$instruction[2]];
                } else {
                    $parameter2 = $instruction[2];
                }
            }

            if (array_key_exists(3, $instruction)) {
                $output = $instruction[3];
            }
        } else {
            $opcode = $rawOpcode;
            $parameter1 = $this->program[$instruction[1]];

            if (array_key_exists(2, $instruction)) {
                $parameter2 = $this->program[$instruction[2]];
            }

            if (array_key_exists(3, $instruction)) {
                $output = $instruction[3];
            }
        }

        if ($opcode == 1) {
            $this->program[$output] = $parameter1 + $parameter2;
        } elseif ($opcode == 2) {
            $this->program[$output] = $parameter1 * $parameter2;
        } elseif ($opcode == 3) {
            if (!$this->input) {
                dd('No input');
            }
            $this->writtenParameters[$instruction[1]] = true;

            $this->program[$instruction[1]] = $this->input;
        } elseif ($opcode == 4) {
            dump($this->program[$instruction[1]]);
        } elseif ($opcode == 5) {
            if ($parameter1 != 0) {
                $this->pointer = $parameter2;
                return;
            }
        } elseif ($opcode == 6) {
            if ($parameter1 == 0) {
                $this->pointer = $parameter2;
                return;
            }
        } elseif ($opcode == 7) {
            if ($parameter1 < $parameter2) {
                $this->program[$output] = 1;
            } else {
                $this->program[$output] = 0;
            }
        } elseif ($opcode == 8) {
            if ($parameter1 == $parameter2) {
                $this->program[$output] = 1;
            } else {
                $this->program[$output] = 0;
            }
        } else {
            dd('Wrong opcode', $opcode, $rawOpcode, $this->pointer, $this->program);
        }

        $this->pointer += $this->opcodeLength[$opcode];
    }
}