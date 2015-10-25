<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;
use Liquid\Registry;
use ReflectionClass;

class RegistryBuilder implements BuilderInterface
{
  use Traits\SingletonTrait; 

  protected $format = [
    'name' => 'string',
  ];

  public function make(array $config)
  {
    $name = isset($config['name']) ? (string)$config['name'] : null;
    return new Registry($name);
  }
}
