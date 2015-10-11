<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Nodes\BaseNode;
use ReflectionClass;

class NodeBuilder implements BuilderInterface
{
  protected $class;

  protected $format = [
    'name' => 'string',
  ];

  protected $namespace = 'Liquid\Nodes\Node';

  public function make(array $config)
  {
    $config = $this->_format($config);
    if (!$config) return;

    $class = new ReflectionClass($this->namespace);
    if (!$class->isSubclassOf('Liquid\Nodes\BaseNode')) return;
    if (!$class->isInstantiable()) return;
    return $class->newInstance($config['name']);
  }

  protected function _format(array $config)
  {
    $output = [];
    foreach ($this->format as $key => $type) {
      if (!array_key_exists($key, $config)) return false;
      if (gettype($config[$key]) != $type) return false;
      $output[$key] = $config[$key];
    }
    return $output;
  }
}
