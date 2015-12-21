<?php
namespace Liquid\Processors;

use Liquid\Processors\Units\UnitStack;
use Liquid\Processors\Units\ProcessUnitInterface;
use Liquid\Processors\Algorithms\AlgorithmInterface;

class PolicyProcessor extends BaseProcessor
{
  protected $policies;

	protected $rewards;

  public function __construct($name = null)
  {
    parent::__construct($name);
		$this->policies = new UnitStack;
    $this->rewards = new UnitStack;
  }

	public function registerPolicy(AlgorithmInterface $policy)
	{
		$this->policies->attach($policy);
	}

	public function registerReward(ProcessUnitInterface $reward)
	{
		$this->rewards->attach($reward);
	}

	public function process(Collection $collection)
	{
    $record = $collection->merge();
		foreach ($this->policies as $policy) {
      $closure = $policy->compile()->bindTo($this);
      if (!$closure($record)) return $record;
    }

    foreach ($this->rewards as $reward) {
      $closure = $reward->compile()->bindTo($this);
      $record = $closure($record);
    }

    return $record;
	}
}
