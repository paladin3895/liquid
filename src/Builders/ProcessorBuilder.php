<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Builders\UnitBuilder;

use Liquid\Processors\BaseProcessor;
use ReflectionClass;
use Exception;

class ProcessorBuilder implements BuilderInterface
{
  use Traits\SingletonTrait, Traits\FormatTrait;

  protected $unitBuilder;
  protected $closureBuilder;

  protected $namespace = 'Liquid\Processors\\';

  protected $format = [
    'class' => 'string',
    'name' => 'string',
    'units' => 'array',
  ];

  public function make(array $config)
  {
    $config = $this->_format($config);

    $class = new ReflectionClass($this->namespace . $config['class']);

    if (!$class->isSubclassOf(BaseProcessor::class))
        throw new Exception("invalid node class in {__CLASS__} at {__FILE__}, line {__LINE__}");

    if (!$class->isInstantiable())
        throw new Exception("uninstantiable class provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

    $processor = $class->newInstance($config['name']);

    foreach ($config['units'] as $unitConfig) {
      if (!isset($unitConfig['class'])) continue;

      $closure = UnitBuilder::getBuilder()->make($unitConfig);
      $processor->chain($closure);
    }
    
    return $processor;
  }
}
