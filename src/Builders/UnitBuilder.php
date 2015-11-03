<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Units\ProcessUnitInterface;
use ReflectionClass;
use Exception;

class UnitBuilder implements BuilderInterface
{
  use Traits\SingletonTrait;

  protected $format = [
    'class' => 'string',
  ];

  protected $namespace = 'Liquid\Processors\Units\\';

  public static function getFormats()
  {
    $units_path = dirname(__DIR__) . "/Processors/Units/*.php";
    foreach (glob($units_path) as $filename)
    {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Processors\\\Units\\\(\w+)#', $class, $matches)) continue;
      if (!is_callable([$class, 'getFormat'])) continue;
      $formats[$matches[1]] = $class::getFormat();
    }
    return $formats;
  }

  public function make(array $config)
  {
    $config = $this->_format($config);

    $class = new ReflectionClass($this->namespace . $config['class']);

    if (!$class->implementsInterface(ProcessUnitInterface::class))
      throw new Exception("invalid unit class provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

    if (!$class->getMethod('validate')->invoke(null, $config))
      throw new Exception("invalid config passed to unit builder {__CLASS__} at {__FILE__}, line {__LINE__}");

    $closure = $class->getMethod('compile')->invoke(null, $config['arguments'] ? : []);
    return $closure;
  }

  protected function _format(array $config)
  {
    if (!isset($config['class']))
      throw new Exception("invalid class provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

    if (!is_callable([$this->namespace . $config['class'], 'getFormat']))
      throw new Exception("unit class {$config['class']} does not provide format in {__CLASS__} at {__FILE__}, line {__LINE__}");

    $this->format['arguments'] = call_user_func(
      [$this->namespace . $config['class'], 'getFormat']
    );

    $output['class'] = $config['class'];
    foreach ($this->format['arguments'] as $key => $type) {
      if (!array_key_exists($key, $config))
        throw new Exception("invalid config field {$key} provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

      if (gettype($config[$key]) != $type)
        throw new Exception("invalid data type provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

      $output['arguments'][$key] = $config[$key];
    }
    return $output;
  }
}
