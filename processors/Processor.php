<?php

abstract class Processor
{
	protected $processUnits = [];

	public function __construct(array $units)
	{
		foreach ($units as $unit) {
			if ($unit !instanceof ProcessUnitInterface) throw new Exception('invalid unit passed to Processor');
			$this->processUnits[] = $unit;
		}
	}

	abstract public function process(array $input);
}