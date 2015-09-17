<?php

abstract class BaseNode
{
	public static $objectPool = [];

	protected $name = "";
	protected $previous = [];
	protected $nexts = [];

	protected $registry = null;
	protected $processor = null;

	protected $depth = 0;

	protected $input = [];
	protected $output = [];


	public function __construct($name, Registry $registry, Processor $processor)
	{
		$this->registry = $registry;
		$this->processor = $processor;
		$this->name = (string)$name;
		self::$objectPool[$name] = &$this;
	}

	// create a piping stucture in which
	// output of one node is input of the nexts
	public function forward(BaseNode &$next)
	{
		$this->nexts[] = $next;
		$next->previous[] = $this;
		$next->depth = ($next->depth > $this->depth) ? $next->depth : ($this->depth + 1);
	}

	public function backward(BaseNode &$previous)
	{
		$this->previous[] = $previous;
		$previous->nexts[] = $this;
		$this->depth = ($this->depth > $previous->depth) ? $this->depth : ($previous->depth + 1);
	}

	public function hub(array $previouses)
	{
		foreach ($previouses as &$node) {
			if (!($node instanceof BaseNode)) throw new Exception('invalid node type');
			$this->backward($node);
		}
	}

	public function split(array $nexts)
	{
		foreach ($nexts as &$node) {
			if (!($node instanceof BaseNode)) throw new Exception('invalid node type');
			$this->forward($node);
		}
	}

	public function process()
	{
		foreach ($this->previous as $node) {
			$this->input[$node->name] = &$node->output;
		}
		$this->output = $this->processor->process($this->input);
	}

	public function terminate()
	{
		// TODO: implement logic here
	}

	public function initialize()
	{
		$this->register();
		foreach ($this->nexts as $node) {
			$node->initialize();
		}
	}

	public function getDepth()
	{
		return $this->depth;
	}

	public function getName()
	{
		return $this->name;
	}

	public function register()
	{
		if ($this->registry->hasRegistered($this)) return;
		$this->registry->register($this);
	}

	public function unregister()
	{
		if (!$this->registry->hasRegistered($this)) return;
		$this->registry->unregister($this);
	}
}
