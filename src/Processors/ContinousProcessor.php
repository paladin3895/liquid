<?php
namespace Liquid\Processors;

use Liquid\Processors\Processor;

class ContinousProcessor extends Processor
{

	public function process(array $data)
	{
		$output = [];
		foreach ($data as $label => $record) {
			$output = array_merge($output, $record);
			foreach ($this->processUnits as $unit) {
				$unit->setLabel($label);
				$output = $unit->process($output);
			}
		}
		return $output;
	}
}
