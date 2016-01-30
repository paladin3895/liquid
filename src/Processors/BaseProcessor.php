<?php

namespace Liquid\Processors;

use Closure;
use Liquid\Nodes\BaseNode;
use Liquid\Records\Collection;
use Liquid\Interfaces\MessageInterface;

abstract class BaseProcessor
{
	protected $name;

	protected $node;

	public function __construct($name = null)
	{
		$this->name = isset($name) ? (string)$name : uniqid('proc_');
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
