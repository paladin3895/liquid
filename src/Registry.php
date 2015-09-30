<?php

namespace Liquid;

use Liquid\Nodes\BaseNode;
use SplObjectStorage;

class Registry
{
	protected $name;

	protected $registries = [];

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
		if (isset($this->registries[$index]) && ($this->registries[$index] instanceof SplObjectStorage))
			return $this->registries[$index];
		else
			return $this->registries[$index] = new SplObjectStorage;
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

	public function run()
	{
		foreach ($this->registries as $depth) {
			foreach ($depth as $node) {
				$node->process();
			}
		}
	}

	public function setInput(array $data)
	{
		$index = min(array_keys($this->registries));
		foreach ($this->registries[$index] as $node) {
			$node->setInput($data);
		}
	}

	public function getOutput()
	{
		$index = max(array_keys($this->registries));
		$output = [];
		foreach ($this->registries[$index] as $node) {
			$output = array_merge($output, $node->getOutput());
		}
		return $output;
	}
}
