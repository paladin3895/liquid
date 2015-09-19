<?php
require_once('ContinousProcessor.php');
require_once('ParalelProcessor.php');

abstract class Processor
{
	protected $processUnits = [];

	public function __construct(array $units)
	{
		foreach ($units as &$unit) {
			if (!($unit instanceof ProcessUnitInterface)) throw new Exception('invalid unit passed to Processor');
			$this->processUnits[] = $unit;
		}
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
