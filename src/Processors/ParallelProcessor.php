<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Units\ProcessUnitInterface;
use Liquid\Records\Collection;
use Liquid\Records\Record;

class ParallelProcessor extends BaseProcessor
{
	public static $alias = 'parallel';

	public function process(Collection $collection)
	{
		return $record;
	}
}
