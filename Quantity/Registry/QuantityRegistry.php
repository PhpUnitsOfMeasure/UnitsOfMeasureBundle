<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Registry;

use PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Definition\QuantityDefinitionInterface;
use Psr\Log\InvalidArgumentException;

class QuantityRegistry implements QuantityRegistryInterface
{
    private $definitions = array();

    public function setDefinition(QuantityDefinitionInterface $definition)
    {
        $name = strtolower($definition->getQuantityName());

        if(!$name) {
            throw new InvalidArgumentException('Empty quantity names are not allowed');
        }

        $this->definitions[$name] = $definition;

        return $this;
    }

    /**
     * @param $name
     * @return QuantityDefinitionInterface
     */
    public function getDefinition($name)
    {
        $name = strtolower($name);

        if (!isset($this->definitions[$name])) {
            throw new \InvalidArgumentException('Unknown quantity: "' . $name . '"');
        }

        return $this->definitions[$name];
    }

    public function createUnit($quantity, $value, $unit)
    {
        return $this->getDefinition($quantity)->createUnit($value, $unit);
    }
}