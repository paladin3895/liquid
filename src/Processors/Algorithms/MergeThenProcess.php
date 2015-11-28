<?php
namespace Liquid\Processors\Algorithms;

use Liquid\Processors\Algorithms\AlgorithmInterface;
use Liquid\Processors\Units\UnitStack;
use Liquid\Records\Collection;

class MergeThenProcess implements AlgorithmInterface
{

	public static function getFormat()
  {

  }

	public static function validate(array $config)
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
