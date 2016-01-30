<?php
namespace Liquid\Processors\Units;

use Liquid\Interfaces\ProcessUnitInterface;
use Liquid\Helpers\Condition;
use Liquid\Helpers\Expression;
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

  public function __construct(array $conditions, array $computations)
  {
    $this->conditions = Condition::make($conditions);
    $this->computations = Expression::make($computations);
  }

  public function compile()
  {
    $conditions = $this->conditions;
    $computations = $this->computations;
    return function (Record $record) use ($conditions, $computations) {
      if ($conditions($record->data)) {
        $record->result = $computations($record->data, $record->result);
      }
      return $record;
    };
  }
}
