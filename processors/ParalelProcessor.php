<?php

class ParalelProcessor extends Processor
{
	public function process(array $input)
	{
		$output = []
		foreach ($this->processUnits as $unit) {
			$output[] = $unit->process($input);
		}
	}
}