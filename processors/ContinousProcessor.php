<?php

class ContinousProcessor extends Processor
{

	public function process(array $data)
	{
		$output = [];
		foreach ($data as $label => $record) {
			foreach ($this->processUnits as &$unit) {
				$unit->setLabel($label);
				$record = $unit->process($record);
			}
			$output = array_merge($output, $record);
		}
		return $output;
	}
}
