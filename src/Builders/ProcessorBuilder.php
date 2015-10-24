<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Builders\UnitBuilder;
use Liquid\Builders\ClosureBuilder;

use Liquid\Processors\BaseProcessor;
use ReflectionClass;
use Exception;

class ProcessorBuilder implements BuilderInterface
{
  use Traits\FormatTrait;

  protected $unitBuilder;
  protected $closureBuilder;

  protected $namespace = 'Liquid\Processors\\';

  protected $format = [
    'class' => 'string',
    'name' => 'string',
    'units' => 'array',
  ];

  public function __construct()
  {
    $this->unitBuilder = new UnitBuilder;
    $this->closureBuilder = new ClosureBuilder;
  }

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

      if ($unitConfig['class'] == 'Closure') {
        $unit = $this->closureBuilder->make($unitConfig);
        if ($unit) $processor->chain($unit);
      } else {
        $unit = $this->unitBuilder->make($unitConfig);
        if ($unit) $processor->stack($unit);
      }
    }
    return $processor;
  }
}
