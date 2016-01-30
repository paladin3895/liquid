<?php
namespace Liquid\Processors;

use Liquid\Interfaces\ConfigurableInterface;

use Liquid\Processors\Units\UnitStack;
use Liquid\Processors\Units\Policies\BasePolicy;
use Liquid\Processors\Units\Rewards\BaseReward;
use Liquid\Records\Collection;

class CycleProcessor extends BaseProcessor implements ConfigurableInterface
{
  protected $policies;

	protected $rewards;

  protected $number;

  public static function getFormat()
  {
    return [
      'class' => 'Liquid\Processors\CycleProcessor',
      'number' => 0
    ];
  }

  public static function validate(array $config)
  {
    $result = [];
    if (isset($config['number']) && is_int($config['number'])) {
      $result['number'] = $config['number'];
    } else {
      throw new \Exception('invalid processor config: number');
    }
    return $result;
  }

  public function __construct($number, $name = null)
  {
    parent::__construct($name);
		$this->policies = new UnitStack;
    $this->rewards = new UnitStack;
    $this->number = $number;
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

    if ($record->status >= $this->number) return $record;

		foreach ($this->policies as $policy) {
      $closure = $policy->compile()->bindTo($this);
      if (!$closure($record)) {
        return $record;
      }
    }

    foreach ($this->rewards as $reward) {
      $closure = $reward->compile()->bindTo($this);
      $record = $closure($record);
    }
    $record->status++;
    return $record;
	}
}
