<?php /** @noinspection PhpUnhandledExceptionInspection */

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

    public function create(int $quantity = 1): void
    {
        while (!$this->hasNeededReactants($quantity)) {
            $this->createNeededReactants($quantity);
        }
        $this->react($this->getReactionsRequiredToProduce($quantity));
    }

    public function createMax(): void
    {
        $power = 9;
        while ($power >= 0) {
            if ($this->hasEnoughBaseElementToCreate(10 ** $power)) {
                $this->create(10 ** $power);
            } else {
                $power--;
            }
        }
        while (true) {
            try {
                $this->create();
            } catch (Exception $exception) {
                break;
            }
        }
    }

    private function react(int $multiple): void
    {
        foreach ($this->neededReactants as $reactant) {
            $reactant->element->amountUsed += ($reactant->moles * $multiple);
            if ($reactant->element->getQuantityLeft() < 0) {
                throw new Exception("Error: " . $reactant->element->name . " ran out of units");
            }
        }
        $this->amountCreated += ($this->reactionCreates * $multiple);
    }

    private function createNeededReactants(int $quantity): void
    {
        foreach ($this->neededReactants as $reactant) {
            $this->createNeededReactant($reactant, $quantity);
        }
    }

    private function getReactionsRequiredToProduce(int $quantity): int
    {
        return ceil($quantity / $this->reactionCreates);
    }

    private function createNeededReactant(Moles $reactant, int $productQuantity): void
    {
        $reactions = $this->getReactionsRequiredToProduce($productQuantity);
        $reactantNeeded = $reactions * $reactant->moles;
        $reactantToProduce = $reactantNeeded - $reactant->element->getQuantityLeft();
        if ($reactantToProduce > 0 && count($reactant->element->neededReactants) === 0) {
            throw new Exception("Error: cannot create base element");
        } elseif ($reactantToProduce > 0) {
            $reactant->element->create($reactantToProduce);
        }
    }

    private function hasNeededReactants(int $quantity): bool
    {
        $reactions = $this->getReactionsRequiredToProduce($quantity);
        foreach ($this->neededReactants as $reactant) {
            $reactantNeeded = $reactions * $reactant->moles;
            if ($reactantNeeded > $reactant->element->getQuantityLeft()) {
                return false;
            }
        }
        return true;
    }

    private function hasEnoughBaseElementToCreate(int $quantity): bool
    {
        $baseElementLeft = $this->getBaseElementLeft();
        $baseElementNeeded = $this->getBaseElementNeededToCreate($quantity);
        return $baseElementLeft >= $baseElementNeeded;
    }

    private function getBaseElementLeft(): int
    {
        $element = $this;
        while (count($element->neededReactants) !== 0) {
            $element = $element->neededReactants[0]->element;
        }
        return $element->getQuantityLeft();
    }

    private function getBaseElementNeededToCreate(int $quantity): int
    {
        $reactions = $this->getReactionsRequiredToProduce($quantity);
        $baseElementNeeded = 0;
        foreach ($this->neededReactants as $reactant) {
            $reactantNeeded = $reactions * $reactant->moles;
            if (count($reactant->element->neededReactants) === 0) {
                return $reactantNeeded;
            }
            $reactantToProduce = $reactantNeeded - $reactant->element->getQuantityLeft();
            if ($reactantToProduce > 0) {
                $baseElementNeeded += $reactant->element->getBaseElementNeededToCreate($reactantNeeded);
            }
        }
        return $baseElementNeeded;
    }


}