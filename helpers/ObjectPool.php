<?php

class ObjectPool
{
	protected $pool = [];

	public function push(BaseNode $node)
	{
		if (array_key_exists($node->getName(), $this->pool)) return;
		else $this->pool[$node->getName()] = $node;
	}

	public function pop(BaseNode $node)
	{
		if (!array_key_exists($node->getName(), $this->pool)) return;
		else unset($this->pool[$node->getName()]);
	}
}