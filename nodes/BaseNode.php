<?php

abstract class BaseNode
{
	protected $name = "";
	protected $previouses;
	protected $nexts;

	protected $registry = null;
	protected $processor = null;

	protected $depth = 0;

	protected $input = [];
	protected $output = [];

	public function __construct($name, Registry $registry)
	{
		$this->previouses = new SplObjectStorage;
		$this->nexts = new SplObjectStorage;

		$this->registry = $registry;
		$this->name = (string)$name;
	}

	public function bind(Processor $processor)
	{
		$this->processor = $processor;
		$processor->bind($this);
	}

	// create a piping stucture in which
	// output of one node is input of the nexts
	public function forward(BaseNode &$next)
	{
		if (!$this->nexts->contains($next)) $this->nexts->attach($next);
		if (!$next->previouses->contains($this)) $next->previouses->attach($this);
		$next->depth = ($next->depth > $this->depth) ? $next->depth : ($this->depth + 1);
	}

	public function backward(BaseNode &$previous)
	{
		if (!$this->previouses->contains($previous)) $this->previouses->attach($previous);
		if (!$previous->nexts->contains($this)) $previous->nexts->attach($this);
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
		$this->_pull();
		$this->output = $this->processor->process($this->input);
		// $this->_push();
	}

	public function terminate()
	{
		$this->unregister();
		foreach ($this->nexts as $node) {
			$node->terminate();
		}
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

	public function display()
	{
		echo '########## Node ##########<br/>';
		echo 'name: ' . $this->name . ', depth: ' . $this->depth . '<br/>';
	}

	public function handleMessage(MessageInterface $message)
	{
		if ($this->processor instanceof MessengerInterface)
			$this->processor->handle($message);
		$message->mark($this);
	}

	public function broadcastMessage(MessageInterface $message)
	{
		foreach ($this->previouses as $node) {
			if ($message->isMarked($node)) continue;
			$node->handleMessage($message);
			$node->broadcastMessage($message);
		}

		foreach ($this->nexts as $node) {
			if ($message->isMarked($node)) continue;
			$node->handleMessage($message);
			$node->broadcastMessage($message);
		}
	}

	public function forwardMessage(MessageInterface $message)
	{
		foreach ($this->nexts as $node) {
			if ($message->isMarked($node)) continue;
			$node->handleMessage($message);
			$node->forwardMessage($message);
		}
	}

	public function backwardMessage(MessageInterface $message)
	{
		foreach ($this->previouses as $node) {
			if ($message->isMarked($node)) continue;
			$node->handleMessage($message);
			$node->backwardMessage($message);
		}
	}

	public function setInput(array $data)
	{
		$this->input = $data;
	}

	public function getOutput()
	{
		return $this->output;
	}

	protected function _push()
	{
		foreach ($this->nexts as $node) {
			$node->input[$this->name] = $this->output;
		}
	}

	protected function _pull()
	{
		if ($this->previouses->count() == 0) $this->input['void'] = ['placeholder'];
		foreach ($this->previouses as $node) {
			$this->input[$node->name] = $node->output;
		}
	}
}
