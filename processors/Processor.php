<?php

abstract class Processor
{
	protected $processUnits;
	protected $node;

	public function __construct(array $units)
	{
		$this->processUnits = new SplObjectStorage;
		foreach ($units as &$unit) {
			if (!($unit instanceof ProcessUnitInterface)) throw new Exception('invalid unit passed to Processor');
			$this->processUnits->attach($unit);
			$unit->bind($this);
		}
	}

	public function bind(BaseNode $node)
	{
		$this->node = $node;
	}

	public function getNode()
	{
		return $this->node;
	}

	/*
	 * $input into the processor with format
	 * ['node_name' => ['key' => 'scalar value', ...], ...]
	 * after process the output format ['key' => 'scalar value', ...]
	 * which will be encapsulated into
	 * ['node_name' => ['key' => 'scalar value', ...], ...]
	 * at the next node to keep data format consistent
	 */
	abstract public function process(array $data);
}
