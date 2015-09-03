<?php

interface ParserInterface
{
	public function parse(array $input);
}

class BaseNode
{
	protected $name = "";
	protected $previous = [];
	protected $nexts = [];

	protected $registry = null;
	protected $processor = null;

	protected $depth = 0;

	protected $input = [];
	protected $output = [];


	public function __construct(Registry $registry, ProcessorInterface $processor, $name = "")
	{
		$this->registry = $registry;
		$this->processor = $processor;
		$this->name = !empty($name) ? (string)$name : (string)"node_{uniqid()}";
	}
	// create a piping stucture in which
	// output of one node is input of the nexts
	public function pipe(BaseNode $next)
	{
		$maxDepth = 0;
		foreach (func_get_args() as &$parameter) {
			if ($parameter !instanceof BaseNode)
				throw new Exception('parameter passed to piping is not valid');
			$this->previous[] = $parameter;
			$parameter->nexts[] = $this;
			$maxDepth = ($parameter->depth > $maxDepth) ? $parameter->depth : $maxDepth;
		}
		$this->depth = $maxDepth + 1;
	}

	public function process()
	{
		foreach ($this->previous as $node) {
			$this->input[$node->name] = &$node->output;
		}
		$this->output = $this->processor->process($this->input);
	}

	public function getDepth()
	{
		return $this->depth;
	}

	public function register()
	{
		if ($this->registry->hasRegistered($this)) return;
		$this->registry->register($this);
		foreach ($this->nexts as $node) {
			$node->register();
		}
	}

}

class Registry
{
	public $registries = [];

	public function register(BaseNode &$node)
	{
		$this->registries[$node->getDepth()][] = $node;
	}

	public function hasRegistered(BaseNode &$node)
	{
		return in_array($node, $this->registries[$node->getDepth()]);
	}

	public function display()
	{
		foreach ($this->registries as $key => $value) {
			echo 'depth ' . $key . ' has ' . count($value) . ' node:<br>';
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
}