<?php

namespace Liquid\Processors;

use Closure;
use Liquid\Processors\Units\UnitStack;
use Liquid\Processors\Units\ProcessUnitInterface;
use Liquid\Nodes\BaseNode;
use Liquid\Records\Collection;

abstract class BaseProcessor
{
	protected $name;
	protected $processUnits;
	protected $algorithm;
	protected $node;

	public function __construct($name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('proc_');
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

	public function bind(BaseNode $node)
	{
		$this->node = $node;
	}

	public function getNode()
	{
		return $this->node;
	}

	public function getName()
	{
		return $this->name;
	}

	public function trigger(MessageInterface $message)
	{
		$this->node->handle($message);
	}

	public function process(Collection $collection)
	{
		return call_user_func(
			$this->algorithm->compile($this->processUnits)->bindTo($this),
			$collection
		);
	}
}
