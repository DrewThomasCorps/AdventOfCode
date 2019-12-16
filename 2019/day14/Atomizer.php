<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 2019-12-14
 * Time: 16:16
 */

require_once __DIR__ . "/Element.php";
require_once __DIR__ . "/Moles.php";

class Atomizer
{
    private array $elements = [];

    public function __construct(array $input)
    {
        $this->formElements($input);
    }

    public function getElement(string $name): Element
    {
        return $this->elements[$name];
    }

    private function formElements(array $input): void
    {
        foreach ($input as $reaction) {
            $this->parseReaction($reaction);
        }
    }

    private function parseReaction(array $reaction): void
    {
        [$reactants, $product] = $reaction;
        $product = $this->parseProduct($product);
        $reactants = $this->getReactants($reactants);
        $this->addReactantsToProduct($reactants, $product);
    }

    private function parseProduct(string $product): Element
    {
        $product = str_replace("> ", "", $product);
        return $this->createProduct($product);
    }

    private function createProduct(string $element): Element
    {
        [$moles, $name] = Moles::parse($element);
        $existingElement = $this->createElement($name);
        $existingElement->reactionCreates = $moles;
        return $existingElement;
    }

    private function getReactants(string $reactants): array
    {
        return explode(", ", trim($reactants));
    }

    private function createElement(string $name): Element
    {
        if (!isset($this->elements[$name])) {
            $this->elements[$name] = new Element($name);
        }
        return $this->getElement($name);
    }

    private function addReactantsToProduct(array $reactants, Element $product)
    {
        foreach ($reactants as $reactant) {
            [$moles, $name] = Moles::parse($reactant);
            $reactant = $this->createElement($name);
            $reactantMoles = new Moles($reactant, $moles);
            $product->neededReactants[] = $reactantMoles;
        }
    }

}