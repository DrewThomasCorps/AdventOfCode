<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 12/28/19
 * Time: 08:44
 */

class Signal
{
    public array $inputSignal;
    public array $outputSignal;
    private PhasePattern $phasePattern;

    public function __construct(array $inputSignal, PhasePattern $phasePattern)
    {
        $this->inputSignal = $inputSignal;
        $this->phasePattern = $phasePattern;
    }

    public function getOutputSignal(): Signal
    {
        for ($i = 0; $i < count($this->inputSignal); $i++) {
            $pattern = $this->phasePattern->getPatternForPosition($i);
            $this->outputSignal[] = $this->getValueForPattern($pattern);
        }
        return new self($this->outputSignal, $this->phasePattern);
    }

    private function getValueForPattern(array $pattern): int
    {
        $total = 0;
        for ($i = 0; $i < count($this->inputSignal); $i++)
        {
            $total += $this->inputSignal[$i] * $pattern[$i];
        }
        return $this->getSinglesDigit($total);
    }

    private function getSinglesDigit(int $number)
    {
        return abs($number % 10);
    }


}