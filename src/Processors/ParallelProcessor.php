<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;

class ParallelProcessor extends BaseProcessor
{
	public function process(array $data)
	{
		$output = [];
		foreach ($data as $label => $record) {
			foreach ($this->processUnits as $unit) {
				if ($unit instanceof ProcessUnitInterface) {
					$unit->setLabel($label);
					$record = $unit->process($record);
				} elseif (is_callable($unit)) {
					$record = $unit($record);
				}
			}
			$output = array_merge($output, $record);
		}
		return $output;
	}
}
