<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity\Definition;

use PhpUnitsOfMeasure\UnitOfMeasure;

class IntegratedQuantityDefinition implements QuantityDefinitionInterface
{
    private $units = array();
    private $quantityName;
    private $quantityClass;

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
        $class = $this->quantityClass;

        if (!class_exists($class)) {
            throw new \RuntimeException('Missing quantity class: ' . $class);
        }

        $quantity = new $class($value, $name);

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