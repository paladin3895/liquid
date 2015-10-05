<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;

class ContinousProcessor extends BaseProcessor
{

	public function process(array $data, array $result_input)
	{
		$output = [];
		$result_output = [];
		foreach ($data as $label => $record) {
			$output = array_merge($output, $record);
			$result_output = array_merge($result_output, $result_input);
			foreach ($this->processUnits as $unit) {
				if ($unit instanceof ProcessUnitInterface) {
					$unit->setLabel($label);
					$output = $unit->process($output);
				} elseif (is_callable($unit)) {
					$result_output = $unit($record, $result_output);
				}
			}
		}
		$this->setOutput($output);
		$this->setResult($result_output);
	}
}
