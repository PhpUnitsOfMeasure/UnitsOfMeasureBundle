<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\Quantity;

use PhpUnitsOfMeasure\PhysicalQuantity;

class ConfiguredQuantity extends PhysicalQuantity
{
    private $name = '';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}