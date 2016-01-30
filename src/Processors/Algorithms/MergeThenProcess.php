<?php
namespace Liquid\Processors\Algorithms;

use Liquid\Interfaces\AlgorithmInterface;
use Liquid\Processors\Units\UnitStack;
use Liquid\Records\Collection;

class MergeThenProcess implements AlgorithmInterface
{

	public static function getFormat()
  {
		return [];
  }

	public static function validate(array $config)
  {
		return true;
  }

	public function __construct()
	{

	}

	public function compile(UnitStack $units)
  {
    return function (Collection $collection) use ($units) {
      $record = $collection->merge();
      foreach ($units as $unit) {
        $closure = $unit->compile()->bindTo($this);
        $record = $closure($record);
      }
      return $record;
    };
  }
}
