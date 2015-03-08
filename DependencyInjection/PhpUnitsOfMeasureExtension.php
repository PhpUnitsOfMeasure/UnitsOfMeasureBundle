<?php

namespace PhpUnitsOfMeasure\Bundle\UnitsOfMeasureBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PhpUnitsOfMeasureExtension extends Extension
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($this->container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config['integrated'] as $name => $quantity) {
            $this->defineIntegratedQuantity($name, $quantity);
        }

        foreach ($config['defined'] as $name => $quantity) {
            $this->defineQuantity($name, $quantity);
        }
    }

    private function defineIntegratedQuantity($name, array $quantity)
    {
        $serviceName = 'php_units_of_measure.quantities.' . $this->normalizeName($name);
        $service = $this->container->register($serviceName, '%php_units_of_measure.quantity.definition.integrated.class%');

        $class = $this->container->getParameter('php_units_of_measure.library.prefix') . ucfirst($name);
        $service->setArguments(array($name, $class));

        foreach ($quantity['units'] as $unitName => $unit) {

            $unitName = $this->normalizeName($unitName);
            $nativeFactor = (float)$unit['to_native_factor'];
            $aliases = $unit['aliases'];

            $service->addMethodCall('defineUnit', array($unitName, $nativeFactor, $aliases));
        }

        $this->container
            ->getDefinition('php_units_of_measure.quantities')
            ->addMethodCall('setDefinition', array(new Reference($serviceName)));
    }

    private function defineQuantity($name, array $quantity)
    {
        $serviceName = 'php_units_of_measure.quantities.' . $this->normalizeName($name);

        if ($this->container->hasDefinition($serviceName)) {
            throw new \RuntimeException('Trying to redefine integrated quantity: ' . $name);
        }

        $service = $this->container->register($serviceName, '%php_units_of_measure.quantity.definition.configured.class%');
        $service->setArguments(array($name, '%php_units_of_measure.quantity.configured.class%'));

        foreach ($quantity['units'] as $unitName => $unit) {

            $unitName = $this->normalizeName($unitName);
            $aliases = $unit['aliases'];

            switch ($unit['type']) {
                case 'native':
                    $nativeFactor = 1;
                    break;
                case 'linear':
                    $nativeFactor = (float)$unit['to_native_factor'];
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid measure type: " . $unit['type']);
            }

            $service->addMethodCall('defineUnit', array($unitName, $nativeFactor, $aliases));
        }

        $this->container
            ->getDefinition('php_units_of_measure.quantities')
            ->addMethodCall('setDefinition', array(new Reference($serviceName)));
    }

    private function normalizeName($name)
    {
        return preg_replace('@[^a-z0-9_]@', '', strtolower($name));
    }
}
