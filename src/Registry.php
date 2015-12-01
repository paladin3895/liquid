<?php

namespace Liquid;

use Liquid\Nodes\BaseNode;
use Liquid\Records\Record;
use SplObjectStorage;

class Registry
{
	protected $name;

	protected $nodes = [];

	public function __construct($name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('reg_');
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

	public function register(BaseNode $node)
	{
		if ($this->hasRegistered($node)) return;
		else $this->getDepth($node->getDepth())->attach($node);
	}

	public function unregister(BaseNode $node)
	{
		if (!$this->hasRegistered($node)) return;
		else $this->getDepth($node->getDepth())->detach($node);
	}

	public function hasRegistered(BaseNode $node)
	{
		return $this->getDepth($node->getDepth())->contains($node);
	}

	public function initialize()
	{
		foreach ($this->objectPool as $node) {
			$node->register($this);
		}
	}

	public function process(array $data)
	{
		if ($data) $this->setInput(new Record($data));
		foreach ($this->nodes as $depth) {
			foreach ($depth as $node) {
				$node->process();
			}
		}
	}

	public function setInput(Record $record)
	{
		$index = min(array_keys($this->nodes));
		foreach ($this->nodes[$index] as $node) {
			$node->setInput($record);
		}
	}
}
