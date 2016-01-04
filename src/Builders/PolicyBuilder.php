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
    'id' => 'integer',
    'policies' => 'array',
    'rewards' => 'array',
  ];

  public function make(array $config)
  {
    $config = $this->_format($config);

    $processor = new PolicyProcessor($config['id']);
    foreach ($config['policies'] as $policy) {
      $policy = $this->_makePolicy($policy);
      $processor->registerPolicy($policy);
    }

    foreach ($config['rewards'] as $reward) {
      $reward = $this->_makeReward($reward);
      $processor->registerReward($reward);
    }

    return $processor;
  }

  public static function getPolicyFormats()
  {
    $policy_path = dirname(__DIR__) . "/Processors/Units/Policies/*.php";
    foreach (glob($policy_path) as $filename) {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Processors\\\Units\\\Policies\\\(\w+)#', $class, $matches)) continue;
      if (!is_callable([$class, 'getFormat'])) continue;
      $format = $class::getFormat();
      $format['class'] = $class;
      $formats[$matches[1]] = $format;
    }
    return $formats;
  }

  public static function getRewardFormats()
  {
    $reward_path = dirname(__DIR__) . "/Processors/Units/Rewards/*.php";
    foreach (glob($reward_path) as $filename) {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Processors\\\Units\\\Rewards\\\(\w+)#', $class, $matches)) continue;
      if (!is_callable([$class, 'getFormat'])) continue;
      $format = $class::getFormat();
      $format['class'] = $class;
      $formats[$matches[1]] = $format;
    }
    return $formats;
  }

  protected function _makePolicy(array $config)
  {
    if (!isset($config['class'])) throw new \Exception('policy with no class name');
    $reflection = new ReflectionClass($config['class']);
    if ($reflection->isSubclassOf(BasePolicy::class) && $reflection->isSubclassOf(BasePolicy::class)) {
      return $reflection->newInstanceArgs($this->_formatConfig($config, $config['class']::getFormat()));
    } else {
      throw new \Exception('policy class is not valid');
    }
  }

  protected function _makeReward(array $config)
  {
    if (!isset($config['class'])) throw new \Exception('reward with no class name');
    $reflection = new ReflectionClass($config['class']);
    if ($reflection->isInstantiable() || $reflection->isSubclassOf(BaseReward::class)) {
      return $reflection->newInstanceArgs($this->_formatConfig($config, $config['class']::getFormat()));
    } else {
      throw new \Exception('reward class is not valid');
    }
  }

  protected function _formatConfig(array $config, array $format) {
    $result = [];
    foreach ($format as $key => $value) {
      $result[$key] = isset($config[$key]) ? $config[$key] : $value;
    }
    return $result;
  }
}
