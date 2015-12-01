<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Processors\Algorithms\AlgorithmInterface;
use ReflectionClass;
use Exception;

class AlgorithmBuilder implements BuilderInterface
{
  use Traits\SingletonTrait;

  protected $format = [
    'class' => 'string',
  ];

  protected $namespace = 'Liquid\Processors\Algorithms\\';

  public static function getFormats()
  {
    $units_path = dirname(__DIR__) . "/Processors/Algorithms/*.php";
    foreach (glob($units_path) as $filename) {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Processors\\\Algorithms\\\(\w+)#', $class, $matches)) continue;
      if (!is_callable([$class, 'getFormat'])) continue;
      $format = $class::getFormat();
      $format['class'] = $matches[1];
      $formats[$matches[1]] = $format;
    }
    return $formats;
  }

  public function make(array $config)
  {
    $config = $this->_format($config);

    $class = new ReflectionClass($this->namespace . $config['class']);

    if (!$class->implementsInterface(AlgorithmInterface::class))
      throw new Exception("invalid algorithm class provided");

    if (!$class->getMethod('validate')->invoke(null, $config['arguments']))
      throw new Exception("invalid config passed to algorithm builder");

    $unit = $class->newInstanceArgs($config['arguments'] ? : []);
    return $unit;
  }

  protected function _format(array $config)
  {
    if (!isset($config['class']))
      throw new Exception("invalid class provided");

    if (!is_callable([$this->namespace . $config['class'], 'getFormat']))
      throw new Exception("algorithm class {$config['class']} does not provide format");

    $this->format['arguments'] = call_user_func(
      [$this->namespace . $config['class'], 'getFormat']
    );

    $output['class'] = $config['class'];
    foreach ($this->format['arguments'] as $key => $type) {
      if (!array_key_exists($key, $config))
        throw new Exception("invalid config field {$key} provided");

      $output['arguments'][$key] = $config[$key];
    }
    return $output;
  }
}
