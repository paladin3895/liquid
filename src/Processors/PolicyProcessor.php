<?php
namespace Liquid\Processors;

use Liquid\Processors\Units\UnitStack;
use Liquid\Processors\Units\Policies\BasePolicy;
use Liquid\Processors\Units\Rewards\BaseReward;
use Liquid\Records\Collection;

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

	public function registerPolicy(BasePolicy $policy)
	{
		$this->policies->attach($policy);
	}

	public function registerReward(BaseReward $reward)
	{
		$this->rewards->attach($reward);
	}

	public function process(Collection $collection)
	{
    $record = $collection->merge();
    $record->fromHistory($this->node);
		foreach ($this->policies as $policy) {
      $closure = $policy->compile()->bindTo($this);
      if (!$closure($record)) {
        $record->status = false;
        return $record;
      }
    }
    foreach ($this->rewards as $reward) {
      $closure = $reward->compile()->bindTo($this);
      $record = $closure($record);
    }
    $record->status = true;
    return $record;
	}
}
