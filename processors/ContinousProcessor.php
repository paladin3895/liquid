<?php

class ContinousProcessor extends Processor
{
	public function process(array $input)
	{
		$output = $input;
		foreach ($this->processUnits as $unit) {
			$output = $unit->process($output);
		}
	}
}