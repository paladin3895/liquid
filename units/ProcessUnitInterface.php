<?php

interface ProcessUnitInterface
{
	/*
	 * $input into the process unit with format
	 * ['key' => 'scalar value', ...]
	 * after process the output format ['key' => 'scalar value', ...]
	 * multiple output of units will be merge into
	 * ['key' => 'scalar value', ...]
	 * at the processor to maintain data format consistency
	 */
	public function process(array $record);

	public function setLabel($label);

	public function getLabel();
}
