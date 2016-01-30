<?php
namespace Liquid\Builders;

use Liquid\Builders\UnitBuilder;
use Liquid\Builders\AlgorithmBuilder;

use Liquid\Processors\Processor;
use ReflectionClass;
use Exception;

class ProcessorBuilder
{
  use Traits\SingletonTrait, Traits\FormatTrait;

  protected $unitBuilder;
  protected $algorithmBuilder;

  protected $namespace = 'Liquid\Processors\\';

  protected $format = [
    'name' => 'string',
    'algorithm' => 'array',
    'units' => 'array',
  ];

  public function initialize(
    AlgorithmBuilder $algorithmBuilder,
    UnitBuilder $unitBuilder
  ) {
    $this->algorithmBuilder = $algorithmBuilder;
    $this->unitBuilder = $unitBuilder;
  }

  public function make(array $config)
  {
    if (!isset($this->unitBuilder) || !isset($this->algorithmBuilder))
      throw new Exception("algorithm builder or unit builder not provided");

    $config = $this->_format($config);

    $processor = new Processor($config['name']);

    $algorithm = $this->algorithmBuilder->make($config['algorithm']);
    $processor->learn($algorithm);

    foreach ($config['units'] as $unitConfig) {
      if (!isset($unitConfig['class'])) continue;
      $unit = $this->unitBuilder->make($unitConfig);
      $processor->chain($unit);
    }

    return $processor;
  }
}
