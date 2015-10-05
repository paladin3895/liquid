<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;

class ContinousProcessor extends BaseProcessor
{

	public function process(array $data)
	{
		$output = [];
		foreach ($data as $label => $record) {
			$output = array_merge($output, $record);
			foreach ($this->processUnits as $unit) {
				if ($unit instanceof ProcessUnitInterface) {
					$unit->setLabel($label);
					$output = $unit->process($output);
				} elseif (is_callable($unit)) {
					$output = $unit($record);
				}
			}
		}
		return $output;
	}
}
