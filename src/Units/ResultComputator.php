<?php
namespace Liquid\Units;

use Liquid\Helpers\Validator;
use Liquid\Helpers\Computator;
use Liquid\Records\Record;

class ResultComputator implements ProcessUnitInterface
{
  public static function getFormat()
  {
    return [
      'conditions' => ["key" => "conditions"],
      'computations' => ["key" => "computations"],
    ];
  }

  public static function compile(array $conditions, array $computations)
  {
    $conditions = Validator::make($conditions);
    $computations = Computator::make($computations);
    return function (Record $record) use ($conditions, $computations) {
      if ($conditions($record->data))
        $record->result = $computations($record->data, $record->result);
      return $record;
    };
  }
}
