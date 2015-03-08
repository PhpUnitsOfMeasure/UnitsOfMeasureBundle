<?php


namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Definition;

interface QuantityDefinitionInterface
{
    public function getQuantityName();
    public function createUnit($value, $name);
}