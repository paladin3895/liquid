<?php
namespace Liquid\Builders;

use Liquid\Builders\BuilderInterface;

use Liquid\Processors\PolicyProcessor;
use Liquid\Processors\Units\Policies\BasePolicy;
use Liquid\Processors\Units\Rewards\BaseReward;
use ReflectionClass;
use Exception;

class PolicyBuilder implements BuilderInterface
{
  use Traits\FormatTrait;

  protected $format = [
    'name' => 'string',
    'policy' => 'array',
    'reward' => 'array',
  ];

  public function make(array $config)
  {
    $config = $this->_format($config);

    $processor = new PolicyProcessor($config['name']);

    foreach ($config['policy'] as $policy) {
      $policy = $this->_makePolicy($policy);
      $processor->registerPolicy($policy);
    }

    foreach ($config['reward'] as $reward) {
      $reward = $this->_makePolicy($reward);
      $processor->registerReward($reward);
    }

    return $processor;
  }

  public function getPolicyFormats()
  {
    $policies_path = dirname(__DIR__) . "/Processors/Units/Policies/*.php";
    foreach (glob($units_path) as $filename) {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Processors\\\Units\\\Policies\\\(\w+)#', $class, $matches)) continue;
      if (!is_callable([$class, 'getFormat'])) continue;
      $format = $class::getFormat();
      $format['class'] = $matches[1];
      $formats[$matches[1]] = $format;
    }
    return $formats;
  }

  public function getRewardFormats()
  {

  }

  protected function _makePolicy(array $config)
  {
    if (!isset($config['class'])) throw new \Exception('policy with no class name');
    $reflection = new ReflectionClass($config['class']);
    if (!$reflection->isInstantiable() || !$reflection->isSubclassOf(BasePolicy::class))
      throw new \Exception('policy class is not valid');
    return $reflection->newInstanceArgs($config);
  }

  protected function _makeReward(array $config)
  {
    if (!isset($config['class'])) throw new \Exception('reward with no class name');
    $reflection = new ReflectionClass($config['class']);
    if (!$reflection->isInstantiable() || !$reflection->isSubclassOf(BaseReward::class))
      throw new \Exception('reward class is not valid');
    return $reflection->newInstanceArgs($config);
  }
}
