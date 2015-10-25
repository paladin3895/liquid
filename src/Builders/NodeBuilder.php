<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Nodes\BaseNode;
use ReflectionClass;
use Exception;

class NodeBuilder implements BuilderInterface
{
  use Traits\SingletonTrait, Traits\FormatTrait;

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
    if (!$class->isSubclassOf('Liquid\Nodes\BaseNode'))
      throw new Exception("invalid node class provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

    if (!$class->isInstantiable())
      throw new Exception("uninstantiable node class provided in {__CLASS__} at {__FILE__}, line {__LINE__}");

    return $class->newInstance($config['name']);
  }
}
