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

  protected $namespace = 'Liquid\Units\\';

  public static function getFormats()
  {
    $units_path = dirname(__DIR__) . "/Units/*.php";
    foreach (glob($units_path) as $filename)
    {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Units\\\(\w+)#', $class, $matches)) continue;
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

    if (!$class->isInstantiable())
      throw new Exception("uninstantiable unit class provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

    $unit = $class->newInstanceArgs($config['arguments'] ? : []);
    $closure = $unit->compile();
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
