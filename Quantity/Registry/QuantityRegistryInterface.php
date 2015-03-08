<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Registry;

use PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Definition\QuantityDefinitionInterface;

interface QuantityRegistryInterface
{
    /**
     * @param $name
     * @return QuantityDefinitionInterface
     */
    public function getDefinition($name);

    public function createUnit($quantity, $value, $unit);
}