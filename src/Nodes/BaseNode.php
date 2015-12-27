<?php
namespace Liquid\Nodes;

use Liquid\Nodes\States\InitialState;
use Liquid\Nodes\States\InactiveState;
use Liquid\Nodes\States\ActiveState;
use Liquid\Processors\BaseProcessor;
use Liquid\Registry;

use Liquid\Records\Collection;
use Liquid\Records\Record;
use Liquid\Messages\MessageInterface;
use Liquid\Nodes\States\StateInterface;

use SplObjectStorage;

abstract class BaseNode
{
	use Traits\RegisteringTrait, Traits\ConnectingTrait;

	const STATUS_ALIVE				= 0b001;
	const STATUS_ACTIVE				= 0b010;
	const STATUS_INITIALIZED	= 0b100;

	protected $name;
	protected $status;

	public $previouses;
	public $nexts;

	protected $depth = 0;

	protected $state;
	public $collection;

	public function __construct($name = null)
	{
		$this->state = new ActiveState;

		$this->previouses = new SplObjectStorage;
		$this->nexts = new SplObjectStorage;

		$this->collection = new Collection;

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

	public function setInput(Record $record)
	{
		$this->collection->push($record);
	}

	public function bind(BaseProcessor $processor)
	{
		$this->processor = $processor;
		$processor->bind($this);
		$this->status |= self::STATUS_ACTIVE;
	}

	public function change(StateInterface $state)
	{
		$this->state = $state;
	}

	abstract public function process();

	abstract public function handle(MessageInterface $message);
}
