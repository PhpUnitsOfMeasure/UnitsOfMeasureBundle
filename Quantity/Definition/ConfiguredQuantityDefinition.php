<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Definition;

use PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\ConfiguredQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;

class ConfiguredQuantityDefinition implements QuantityDefinitionInterface
{
    private $quantityName;
    private $quantityClass;

    private $units = array();

    public function __construct($quantityName, $quantityClass)
    {
        $this->quantityName = $quantityName;
        $this->quantityClass = $quantityClass;
    }

    public function getQuantityName()
    {
        return $this->quantityName;
    }

    public function defineUnit($name, $factor, array $aliases)
    {
        $this->units[$name] = array(
            'factor' => $factor,
            'aliases' => $aliases,
        );
        return $this;
    }

    public function createUnit($value, $name)
    {
        $quantity = new $this->quantityClass($value, $name);
        $quantity->setName($this->quantityName);

        foreach ($this->units as $unitName => $unit) {
            $unitOfMeasure = UnitOfMeasure::linearUnitFactory($unitName, $unit['factor']);

            foreach ($unit['aliases'] as $alias) {
                $unitOfMeasure->addAlias($alias);
            }

            $quantity->registerUnitOfMeasure($unitOfMeasure);
        }

        return $quantity;
    }
}