<?php
namespace Liquid\Builders;

use Liquid\Processors\BaseProcessor;
use Liquid\Processors\Units\Policies\BasePolicy;
use Liquid\Processors\Units\Rewards\BaseReward;
use Liquid\Interfaces\ConfigurableInterface;
use ReflectionClass;
use Exception;

class PolicyBuilder
{
  use Traits\FormatTrait;

  protected $format = [
    'id' => 'integer',
    'policies' => 'array',
    'rewards' => 'array',
    'config' => 'object',
  ];

  public function make(array $config)
  {
    $config = $this->_format($config);

    $processor = $this->_makeProcessor((array)$config['config']);
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

  public static function getProcessorFormats()
  {
    $processor_path = dirname(__DIR__) . "/Processors/*.php";
    foreach (glob($processor_path) as $filename) {
      include_once $filename;
    }

    $formats = [];
    foreach (get_declared_classes() as $class) {
      if (!preg_match('#^Liquid\\\Processors\\\(\w+)#', $class, $matches)) continue;
      if (!is_callable([$class, 'getFormat'])) continue;
      $format = $class::getFormat();
      $format['class'] = $class;
      $formats[$matches[1]] = $format;
    }
    return $formats;
  }

  protected function _makePolicy(array $config)
  {
    return $this->_makeComponent($config, 'policy', BasePolicy::class);
  }

  protected function _makeReward(array $config)
  {
    return $this->_makeComponent($config, 'reward', BaseReward::class);
  }

  protected function _makeProcessor(array $config)
  {
    return $this->_makeComponent($config, 'processor', BaseProcessor::class);
  }

  protected function _makeComponent(array $config, $componentName, $baseClass)
  {
    if (!isset($config['class'])) throw new \Exception("{$componentName} with no class name");
    $reflection = new ReflectionClass($config['class']);
    if (
      $reflection->isInstantiable() &&
      $reflection->isSubclassOf($baseClass) &&
      $reflection->implementsInterface(ConfigurableInterface::class)
    ) {
      return $reflection->newInstanceArgs(
        $reflection->getMethod('validate')->invoke(null, $config)
      );
    } else {
      throw new \Exception("{$componentName} class is not valid");
    }
  }
}
