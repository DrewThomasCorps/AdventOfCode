<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 16:17
 */

class Element
{
    public string $name;
    public int $reactionCreates;
    public array $neededReactants = [];
    public int $amountCreated = 0;
    public int $amountUsed = 0;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getQuantityLeft(): int
    {
        return $this->amountCreated - $this->amountUsed;
    }

    public function synthesizeMax(): void
    {
        $power = 5;
        while ($power >= 0) {
            if (!$this->synthesize(pow(10, $power))) {
                echo "\nHERE!";
                $power--;
            }
        }
    }

    public function synthesize(int $count = 1): bool
    {
        if (!$this->hasEnoughRawMaterials($count)) {
            return false;
        }
        if (count($this->neededReactants) === 0) {
            return $this->getQuantityLeft() >= $count;
        }
        if (!$this->createNeededReactants($count)) {
            return false;
        }
        return $this->react($count);
    }

    private function react(int $multiple = 1): bool
    {
        foreach ($this->neededReactants as $neededReactant) {
            $neededReactant->element->amountUsed += ($neededReactant->moles * $multiple);
            if ($neededReactant->element->getQuantityLeft() < 0) {
                return false;
            }
        }
        $this->amountCreated += ($this->reactionCreates * $multiple);
        return true;
    }

    private function createNeededReactants(int $multiple = 1): bool
    {
        if (count($this->neededReactants) === 0) {
            return $this->getQuantityLeft() >= $multiple;
        }
        while (!$this->hasNeededReactants($multiple)) {
            foreach ($this->neededReactants as $reactant) {
                if (!$this->createReactant($reactant, $multiple)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function hasNeededReactants(int $multiple = 1): bool
    {
        foreach ($this->neededReactants as $neededReactant) {
            if ($neededReactant->element->getQuantityLeft() < ($neededReactant->moles * $multiple)) {
                return false;
            }
        }
        return true;
    }

    private function createReactant(Moles $reactant, int $count = 1): bool
    {
        $reactants = $this->getReactantsRequiredToCreate($reactant, $count);
        if ($reactants > 0) {
            return $reactant->element->synthesize(ceil($reactants / $reactant->element->reactionCreates));
        } else {
            return true;
        }
    }

    private function hasEnoughRawMaterials(int $count = 1): bool
    {
        $totalRawMaterials = $this->getRawMaterials();
        $rawMaterialsNeeded = $this->getNeededRawMaterials(0, $count);
        return $totalRawMaterials >= $rawMaterialsNeeded;
    }

    private function getRawMaterials(): int
    {
        $element = $this;
        while (count($element->neededReactants) !== 0) {
            $element = $element->neededReactants[0]->element;
        }
        return $element->getQuantityLeft();
    }

    public function getNeededRawMaterials(int $carry = 0, int $count = 1): int
    {
        foreach ($this->neededReactants as $reactant) {
            $reactants = $this->getReactantsRequiredToCreate($reactant, $count);
            if (count($reactant->element->neededReactants) === 0) {
                $carry += $reactants;
            } else {
                $carry += $reactant->element->getNeededRawMaterials($carry, $reactants);
            }
        }
        return $carry;
    }

    private function getReactantsRequiredToCreate(Moles $reactant, $count = 1): int
    {
        $productNeeded = $count - $this->getQuantityLeft();
        if ($productNeeded <= 0) {
            return 0;
        }
        $productReactions = ceil($productNeeded / $this->reactionCreates);
        return $productReactions * $reactant->moles;
    }


}