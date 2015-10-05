<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;

class MergingProcessor extends BaseProcessor
{

	public function process(array $data, array $result_input)
	{
    $input = [];
    foreach (array $data as $record) {
      $input = array_merge($input, $record);
    }

		foreach ($this->processUnits as $unit) {
			if ($unit instanceof ProcessUnitInterface) {
				$unit->setLabel($label);
				$output = $unit->process($record);
			} elseif (is_callable($unit)) {
				$result_output = $unit($record, $result_input);
			}
		}

		$this->setOutput($output);
		$this->setResult($result_output);
	}
}
