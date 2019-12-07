<?php /** @noinspection DuplicatedCode */
/** @noinspection PhpUnhandledExceptionInspection */

/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-07
 * Time: 10:58
 */
class IntcodeProgramThree
{
    const ADD = 1;
    const MULTIPLY = 2;
    const INPUT = 3;
    const OUTPUT = 4;
    const JUMP_IF_TRUE = 5;
    const JUMP_IF_FALSE = 6;
    const LESS_THAN = 7;
    const EQUALS = 8;
    const HALT = 99;

    const POSITION_MODE = 0;
    const IMMEDIATE_MODE = 1;

    private array $memory;
    private array $opcodeModes;
    private int $instructionPointer = 0;
    public array $inputs = [];
    public bool $running = false;
    public array $outputs = [];

    private /** @noinspection PhpMissingFieldTypeInspection */
        $outputCallback;

    public function __construct($data, $noun = null, $verb = null)
    {
        $this->memory = $data;
        $this->memory[1] = $noun ?? $this->memory[1];
        $this->memory[2] = $verb ?? $this->memory[2];
    }

    public function execute(): int
    {
        $this->running = true;
        while ((int)$this->memory[$this->instructionPointer] !== self::HALT && $this->running) {
            $this->evaluateOpcode();
        }
        return $this->memory[0];
    }

    public function defineNextInputs(array $inputs)
    {
        $this->inputs = [...array_reverse($inputs), ...$this->inputs];
    }

    public function defineOutputCallback(callable $callback)
    {
        $this->outputCallback = $callback;
    }

    public function pause()
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

    private function evaluateOpcode()
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
            default:
                throw new Exception("Failed at instruction pointer: {$this->instructionPointer} with value: {$this->memory[$this->instructionPointer]}");
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

    private function moveInstructionPointerToValue(int $value)
    {
        $this->instructionPointer = $value - 1;
    }

    private function evaluateNextParameter(): int
    {
        $pointer = ++$this->instructionPointer;
        $mode = (int)array_pop($this->opcodeModes);
        if ($mode === self::POSITION_MODE || $mode === null) {
            return $this->memory[$this->memory[$pointer]];
        }
        if ($mode === self::IMMEDIATE_MODE) {
            return $this->memory[$pointer];
        }
        throw new Exception("\nError: Encountered unknown opcode mode: $mode at {$this->instructionPointer}.");
    }

    private function storeValueWithNextParameter(int $value)
    {
        $storageAddress = $this->memory[++$this->instructionPointer];
        $this->memory[$storageAddress] = $value;
    }

    private function getOpcodeAndModes(): int
    {
        $fullOpcode = $this->memory[$this->instructionPointer];
        $this->opcodeModes = str_split(substr($fullOpcode, 0, -2));
        if (strlen($fullOpcode) > 2) {
            return substr($this->memory[$this->instructionPointer], -2);
        }
        return $fullOpcode;
    }

}