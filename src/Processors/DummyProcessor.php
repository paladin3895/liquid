<?php
namespace Liquid\Processors;

use Liquid\Processors\Units\UnitStack;
use Liquid\Processors\Units\ProcessUnitInterface;
use Liquid\Processors\Algorithms\AlgorithmInterface;
use Liquid\Records\Collection;

class DummyProcessor extends BaseProcessor
{
	public function process(Collection $collection)
	{
    $record = $collection->merge();
    $record->setStatus(true);
		return $record;
	}
}
