<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Nodes\BaseNode;
use ReflectionClass;

class NodeBuilder implements BuilderInterface
{
  protected $class;

  protected $format = [
    'class' => 'string',
    'name' => 'string',
  ];

  public function make(array $config)
  {
    $config = $this->_format($config);
    if (!$config) return;

    $class = new ReflectionClass($config['class']);
    if (!$class->isSubclassOf(BaseNode::class)) return;
    if (!$class->isInstantiable()) return;
    return $class->newInstance($config['name']);
  }

  protected function _format(array $config)
  {
    $output = [];
    foreach ($this->format as $key => $type) {
      if (!array_key_exists($key, $config)) return false;
      if (gettype($config[$key]) != $type) return false;
      $output[$element] = $config[$element];
    }
    return $output;
  }
}
