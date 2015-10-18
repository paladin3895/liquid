<?php
namespace Liquid\Nodes;

use Liquid\Processors\Processor;
use Liquid\Processors\MessengerInterface;
use Liquid\Registry;
use Liquid\Messages\MessageInterface;
use SplObjectStorage;

abstract class BaseNode
{
	const STATUS_ALIVE				= 0b001;
	const STATUS_ACTIVE				= 0b010;
	const STATUS_INITIALIZED	= 0b100;

	protected $name;
	protected $status;
	protected $previouses;
	protected $nexts;

	protected $depth = 0;

	protected $input = [];
	protected $output = [];

	public function __construct($name = null)
	{
		$this->previouses = new SplObjectStorage;
		$this->nexts = new SplObjectStorage;

		$this->name = isset($name) ? (string)$name : uniqid('node_');
		$this->status |= self::STATUS_ALIVE;
	}

	public function getDepth()
	{
		return $this->depth;
	}

	public function getName()
	{
		return $this->name;
	}

	public function display()
	{
		echo '########## Node ##########<br/>';
		echo 'name: ' . $this->name . ', depth: ' . $this->depth . '<br/>';
	}

	public function setInput(array $data)
	{
		$this->input = $data;
	}

	public function getInput()
	{
		return $this->input;
	}

	public function setOutput(array $data)
	{
		$this->output = $data;
	}

	public function getOutput()
	{
		return $this->output;
	}

	use RegisteringTrait, ConnectingTrait, ProcessingTrait;
	use TriggeringTrait;
}
