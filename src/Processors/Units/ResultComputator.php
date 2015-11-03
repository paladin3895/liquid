<?php
namespace Liquid\Processors\Units;

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

  public static function validate(array $config)
  {
    if (!isset($config['conditions'])) return false;
    if (!is_array($config['conditions'])) return false;

    if (!isset($config['computations'])) return false;
    if (!is_array($config['computations'])) return false;

    return true;
  }

  public static function compile(array $config)
  {
    $conditions = Validator::make($config['conditions']);
    $computations = Computator::make($config['computations']);
    return function (Record $record) use ($conditions, $computations) {
      if ($conditions($record->data))
        $record->result = $computations($record->data, $record->result);
      return $record;
    };
  }
}
