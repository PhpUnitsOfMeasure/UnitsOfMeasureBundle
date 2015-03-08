# PhpUnitOfMeasureBundle

This bundle is a Symfony2 integration of the library [triplepoint/php-units-of-measure](https://github.com/triplepoint/php-units-of-measure).

## Terminology

The following words are used with the given definition, to avoid confusion:

* __Quantity__: Describes the type of a unit. Example `Length` or `Mass`.
* __Unit__: Describes the name of a unit. Example `meter` or `kilogram`.
* __Alias__: Another name for a unit. Example `meter` can have the aliases `meters`, `metre` and `metres`.
* __Native Unit__: Every quantity needs a base measurement that will be converted to. Example Length has `meter`, so `feet` will first be converted to `meter` before converted to `inch`.
* __Factor__: A numeric value used to convert a measurement of a unit to another. Example: `kilogram` has the factor 1, while `ton` has 1000.

## Configuration

The configuration allows to enable the integrated units from the library and extend them with own units.
Also own linear units can be simply configured.

```yaml
php_units_of_measure:

  # Define which integrated PhysicalQuantities should be available.
  integrated:
    # Enable the quantity Time without any modifications.
    Time: true
    # Enable the quantity Length with some additional units.
    Length:
      units:
        # A new (fictional) unit, where 1um is defined as 100000000 meter.
        UltraMeter:
          aliases: [ 'um' ]
          to_native_factor: 100000000

  # Define own quantities.
  defined:
    # This quantity is just for demonstration purposes
    Scrum:
      units:
        # Native unit is a single person.
        Persons:
          aliases: [ 'p' ]
          type: native
        # A team consists of 7 persons.
        Teams:
          aliases: [ 't' ]
          to_native_factor: 7
        # A ScrumOfScrum is considered to be 3 teams.
        ScrumOfScrums:
          aliases: [ 'sos' ]
          to_native_factor: 21
```

## Services

There is a service for fetching all available quantities:

```php
$quantities = $this->getContainer()->get('php_units_of_measure.quantities');

// Long form
$lengthDefinition = $quantities->getDefinition('length');
$length = $lengthDefinition->createUnit(10, 'meter');

// Short form
$length = $quantities->createUnit('length', 10, meter);
```

For each enabled quantity, a own service with the definition is available:

```php
$lengthDefinition = $this->getContainer()->get('php_units_of_measure.quantities.length');
$length = $lengthDefinition->createUnit(10, 'meter');
```

This service can be easily injected and used in other dependencies.


## TODO

### Github

- [ ] Create github organization for PhpUnitOfMeasure with @triplepoint

### Twig

- [ ] Integrate this with Twig, that would be cool

### Configuration

- [x] Enable definition of new units via configuration.
- [x] Enabled existing Units via configuration.
- [x] Enable creating new Units for existing Quantities.
- [ ] Enabled adding aliases to existing units. __Does not seem to be possible to add aliases afterwards.__

### Container

- [x] Provide a central point to access all configured Units.
- [ ] Integrate own Quantity classes (via DI Tag?).

