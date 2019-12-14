<?php /** @noinspection DuplicatedCode */
/** @noinspection PhpUnhandledExceptionInspection */

/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-11
 * Time: 13:00
 */
class IntcodeProgramSix
{
    const ADD = 1;
    const MULTIPLY = 2;
    const INPUT = 3;
    const OUTPUT = 4;
    const JUMP_IF_TRUE = 5;
    const JUMP_IF_FALSE = 6;
    const LESS_THAN = 7;
    const EQUALS = 8;
    const ADJUST_RELATIVE_BASE = 9;
    const HALT = 99;

    const POSITION_MODE = 0;
    const IMMEDIATE_MODE = 1;
    const RELATIVE_MODE = 2;

    public array $memory;
    private array $opcodeModes;
    private int $instructionPointer = 0;
    private int $relativeBaseOffset = 0;
    public array $inputs = [];
    public array $outputs = [];
    public bool $running = false;

    private /** @noinspection PhpMissingFieldTypeInspection */
        $outputCallback;
    private /** @noinspection PhpMissingFieldTypeInspection */
        $inputCallback;

    public function __construct(array $data, $noun = null, $verb = null)
    {
        $this->memory = $data;
        if (isset($noun)) {
            $this->memory[1] = $noun;
        }
        if (isset($verb)) {
            $this->memory[2] = $verb;
        }
    }

    public function execute(): int
    {
        $this->running = true;
        while ((int)$this->readFromMemory($this->instructionPointer) !== self::HALT && $this->running) {
            $this->evaluateOpcode();
        }
        return $this->memory[0];
    }

    public function defineNextInputs(array $inputs): void
    {
        $this->inputs = [...array_reverse($inputs), ...$this->inputs];
    }

    public function defineInputCallback($callback): void
    {
        $this->inputCallback = $callback;
    }

    public function defineOutputCallback(callable $callback): void
    {
        $this->outputCallback = $callback;
    }

    public function pause(): void
    {
        $this->running = false;
    }

    public function continue(): void
    {
        if (!isset($this->running)) {
            $this->execute();
        } else {
            $this->running = true;
        }
    }

    private function evaluateOpcode(): void
    {
        $opcode = $this->getOpcodeAndModes();
        switch ($opcode) {
            case self::ADD:
                $this->add();
                break;
            case self::MULTIPLY:
                $this->multiply();
                break;
            case self::INPUT:
                $this->input();
                break;
            case self::OUTPUT:
                $this->output();
                break;
            case self::JUMP_IF_TRUE:
                $this->jumpIfTrue();
                break;
            case self::JUMP_IF_FALSE:
                $this->jumpIfFalse();
                break;
            case self::LESS_THAN:
                $this->lessThan();
                break;
            case self::EQUALS:
                $this->equals();
                break;
            case self::ADJUST_RELATIVE_BASE:
                $this->adjustRelativeBase();
                break;
            default:
                throw new Exception("Failed at instruction pointer: {$this->instructionPointer} with value: {$this->readFromMemory($this->instructionPointer)}");
        }
        $this->instructionPointer++;
    }

    private function add(): void
    {
        $addendOne = $this->evaluateNextParameter();
        $addendTwo = $this->evaluateNextParameter();
        $this->storeValueWithNextParameter($addendOne + $addendTwo);
    }

    private function multiply(): void
    {
        $multiplicandAddress = $this->evaluateNextParameter();
        $multiplierAddress = $this->evaluateNextParameter();
        $this->storeValueWithNextParameter($multiplicandAddress * $multiplierAddress);
    }

    private function input(): void
    {
        if (isset($this->inputCallback)) {
            ($this->inputCallback)();
        }
        if (count($this->inputs) > 0) {
            $input = array_pop($this->inputs);
        } else {
            echo "\nPlease enter your input: ";
            $input = (int)fgets(STDIN);
        }
        $this->storeValueWithNextParameter($input);
    }

    private function output(): void
    {
        $output = $this->evaluateNextParameter();
        $this->outputs[] = $output;
        if (isset($this->outputCallback)) {
            ($this->outputCallback)($output);
        }
    }

    private function jumpIfTrue(): void
    {
        $comparison = $this->evaluateNextParameter();
        $value = $this->evaluateNextParameter();
        if ($comparison !== 0) {
            $this->moveInstructionPointerToValue($value);
        }
    }

    private function jumpIfFalse(): void
    {
        $comparison = $this->evaluateNextParameter();
        $value = $this->evaluateNextParameter();
        if ($comparison === 0) {
            $this->moveInstructionPointerToValue($value);
        }
    }

    private function lessThan(): void
    {
        $firstParam = $this->evaluateNextParameter();
        $secondParam = $this->evaluateNextParameter();
        if ($firstParam < $secondParam) {
            $this->storeValueWithNextParameter(1);
        } else {
            $this->storeValueWithNextParameter(0);
        }
    }

    private function equals(): void
    {
        $firstParam = $this->evaluateNextParameter();
        $secondParam = $this->evaluateNextParameter();
        if ($firstParam === $secondParam) {
            $this->storeValueWithNextParameter(1);
        } else {
            $this->storeValueWithNextParameter(0);
        }
    }

    private function adjustRelativeBase(): void
    {
        $adjust = $this->evaluateNextParameter();
        $this->relativeBaseOffset += $adjust;
    }

    private function moveInstructionPointerToValue(int $value): void
    {
        $this->instructionPointer = $value - 1;
    }

    private function evaluateNextParameter(): int
    {
        $pointer = ++$this->instructionPointer;
        $mode = (int)array_pop($this->opcodeModes);
        $immediate = $this->readFromMemory($pointer);
        if ($mode === self::POSITION_MODE || $mode === null) {
            return $this->readFromMemory($immediate);
        } elseif ($mode === self::IMMEDIATE_MODE) {
            return $immediate;
        } elseif ($mode === self::RELATIVE_MODE) {
            return $this->readFromMemory($immediate + $this->relativeBaseOffset);
        }
        throw new Exception("\nError: Encountered unknown opcode mode: $mode at {$this->instructionPointer}.");
    }

    private function storeValueWithNextParameter(int $value): void
    {
        $relative = 0;
        if ((int)array_pop($this->opcodeModes) === self::RELATIVE_MODE) {
            $relative = $this->relativeBaseOffset;
        }
        $storageAddress = $this->readFromMemory(++$this->instructionPointer) + $relative;
        $this->memory[$storageAddress] = $value;
    }

    private function getOpcodeAndModes(): int
    {
        $fullOpcode = $this->readFromMemory($this->instructionPointer);
        $this->opcodeModes = str_split(substr($fullOpcode, 0, -2));
        if (strlen($fullOpcode) > 2) {
            $fullOpcode = substr($this->readFromMemory($this->instructionPointer), -2);
        }
        $returnOpcode = ltrim($fullOpcode, "0");
        return (int)$returnOpcode;
    }


    private function readFromMemory(int $location): int
    {
        return $this->memory[$location] ?? 0;
    }

}