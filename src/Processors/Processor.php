<?php
namespace Liquid\Processors;

use Liquid\Processors\Units\UnitStack;
use Liquid\Processors\Units\ProcessUnitInterface;
use Liquid\Processors\Algorithms\AlgorithmInterface;
use Liquid\Records\Collection;

class Processor extends BaseProcessor
{
  protected $processUnits;

	protected $algorithm;

  public function __construct($name = null)
  {
      parent::__construct($name);
  		$this->processUnits = new UnitStack;
  }

	public function learn(AlgorithmInterface $algorithm)
	{
		$this->algorithm = $algorithm;
	}

	public function chain(ProcessUnitInterface $unit)
	{
		$this->processUnits->attach($unit);
	}

	public function process(Collection $collection)
	{
		return call_user_func(
			$this->algorithm->compile($this->processUnits)->bindTo($this),
			$collection
		);
	}
}
