<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-02
 * Time: 16:21
 */

class IntcodeProgram
{
    const ADD = 1;
    const MULTIPLY = 2;
    const HALT = 99;

    private array $memory;
    private int $instructionPointer = 0;

    public function __construct($data, $noun, $verb)
    {
        $this->memory = $data;
        $this->memory[1] = $noun;
        $this->memory[2] = $verb;
    }

    public function execute(): int
    {
        while ((int)$this->memory[$this->instructionPointer] !== self::HALT) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->evaluateInstruction();
        }
        return $this->memory[0];
    }

    private function evaluateInstruction()
    {
        switch ($this->memory[$this->instructionPointer]) {
            case self::ADD:
                $this->add();
                break;
            case self::MULTIPLY:
                $this->multiply();
                break;
            default:
                throw new Exception("Failed at instruction pointer: {$this->instructionPointer} 
                    with value: {$this->memory[$this->instructionPointer]}");
        }
    }

    private function add()
    {
        $addend1Address = $this->memory[++$this->instructionPointer];
        $addend2Address = $this->memory[++$this->instructionPointer];
        $storageAddress = $this->memory[++$this->instructionPointer];
        $this->memory[$storageAddress] = $this->memory[$addend1Address] + $this->memory[$addend2Address];
        $this->instructionPointer++;
    }

    private function multiply()
    {
        $multiplicandAddress = $this->memory[++$this->instructionPointer];
        $multiplierAddress = $this->memory[++$this->instructionPointer];
        $storageAddress = $this->memory[++$this->instructionPointer];
        $this->memory[$storageAddress] = $this->memory[$multiplicandAddress] * $this->memory[$multiplierAddress];
        $this->instructionPointer++;
    }


}