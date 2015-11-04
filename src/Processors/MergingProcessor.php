<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;
use Liquid\Records\Collection;
use Liquid\Records\Record;

class MergingProcessor extends BaseProcessor
{
	public static $alias = 'merging';

	public function process(Collection $collection)
	{
		$record = $collection->merge();
		foreach ($this->processUnits as $unit) {
			$closure = $unit->compile()->bindTo($this);
			$record = $closure($record);
		}
		return $record;
	}
}
