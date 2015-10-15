<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;

class ParallelProcessor extends BaseProcessor
{
	public static $alias = 'parallel';

	public function process(array $data, array $result_input)
	{
		$output = [];
		$result_output = [];
		foreach ($data as $label => $record) {
			$result_temp = $result_input;
			foreach ($this->processUnits as $unit) {
				if ($unit instanceof ProcessUnitInterface) {
					$unit->setLabel($label);
					$record = $unit->process($record);
				} elseif (is_callable($unit)) {
					$result_temp = $unit($record, $result_temp);
				}
			}
			$output = array_merge($output, $record);
			$result_output = array_merge($result_output, $result_temp);
		}
		$this->setOutput($output);
		$this->setResult($result_output);
	}
}
