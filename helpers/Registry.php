<?php 

class Registry
{
	protected $registries = [];

	public function getDepth($index)
	{
		$index = (int)$index;
		if (isset($this->registries[$index]) && ($this->registries[$index] instanceof SplObjectStorage))
			return $this->registries[$index];
		else
			return $this->registries[$index] = new SplObjectStorage;
	}

	public function register(BaseNode &$node)
	{
		if ($this->hasRegistered) return;
		else $this->getDepth($node->getDepth())->attach($node);
	}

	public function unregister(BaseNode &$node)
	{
		if (!$this->hasRegistered($node)) return;
		else $this->getDepth($node->getDepth())->dettach($node);
	}

	public function hasRegistered(BaseNode &$node)
	{
		return $this->getDepth($node->getDepth())->contains($node);
	}

	public function display()
	{
		
	}

	public function run()
	{
		foreach ($this->registries as $depth) {
			foreach ($depth as $node) {
				$node->process();
			}
		}
	}
}