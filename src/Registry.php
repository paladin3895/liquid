<?php

namespace Liquid;

use Liquid\Nodes\BaseNode;
use Liquid\Nodes\States\ActiveState;
use Liquid\Records\Record;
use SplObjectStorage;

class Registry
{
	protected $name;

	protected $nodes = [];

	protected $objectPool;

	public function __construct($name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('reg_');
		$this->objectPool = new SplObjectStorage;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDepth($index)
	{
		$index = (int)$index;
		if (isset($this->nodes[$index]) && ($this->nodes[$index] instanceof SplObjectStorage))
			return $this->nodes[$index];
		else
			return $this->nodes[$index] = new SplObjectStorage;
	}

	public function attach(BaseNode $node)
	{
		if (!$this->objectPool->contains($node))
			$this->objectPool->attach($node);
	}

	public function detach(BaseNode $node)
	{
		if ($this->objectPool->contains($node))
			$this->objectPool->detach($node);
	}

	public function initialize()
	{
		foreach ($this->objectPool as $node) {
			$this->getDepth($node->getDepth())->attach($node);
		}
	}

	public function process(Record $record)
	{
		$this->setInput($record);
		foreach ($this->nodes as $depth) {
			foreach ($depth as $node) {
				$node->process();
			}
		}
	}

	public function setInput(Record $record)
	{
		if (empty($this->nodes)) return;
		$index = min(array_keys($this->nodes));
		foreach ($this->nodes[$index] as $node) {
			$node->setInput(clone $record);
			$node->change(new ActiveState);
		}
	}
}
