<?php

namespace Liquid\Processors;

use SplObjectStorage;
use Closure;
use Liquid\Units\ProcessUnitInterface;
use Liquid\Nodes\BaseNode;

abstract class BaseProcessor
{
	protected $name;
	protected $processUnits = [];
	protected $node;

	public function __construct($name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('proc_');
	}

	public function chain(Closure $closure)
	{
		$this->processUnits[] = $closure->bindTo($this);
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
		$this->node->handleMessage($message);
	}

	/*
	 * $input into the processor with format
	 * ['node_name' => ['key' => 'scalar value', ...], ...]
	 * after process the output format ['key' => 'scalar value', ...]
	 * which will be encapsulated into
	 * ['node_name' => ['key' => 'scalar value', ...], ...]
	 * at the next node to keep data format consistent
	 */
	abstract public function process(Collection $collection);
}
