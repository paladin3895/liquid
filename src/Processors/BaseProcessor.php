<?php

namespace Liquid\Processors;

use SplObjectStorage;
use Closure;
use Liquid\Processors\Units\ProcessUnitInterface;
use Liquid\Nodes\BaseNode;
use Liquid\Records\Collection;

abstract class BaseProcessor
{
	protected $name;
	protected $processUnits;
	protected $algorithms;
	protected $node;

	public function __construct($name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('proc_');
		$this->processUnits = new SplObjectStorage;
	}

	// public function chain(Closure $closure)
	// {
	// 	$this->processUnits[] = $closure->bindTo($this, static::class);
	// }

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

	abstract public function process(Collection $collection);
}
