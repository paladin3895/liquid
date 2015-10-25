<?php

namespace Liquid\Units;

use Liquid\Records\Record;

class ResultComputator extends BaseUnit implements FormatInterface
{
  use Traits\ValidationTrait, Traits\ExpressionTrait;

  public static function getFormat()
  {
    return [
      'conditions' => 'array',
      'computations' => 'array',
    ];
  }

  protected $conditions;
  protected $computations;

  public function __construct(array $conditions, array $computations, $name = null)
  {
    parent::__construct($name);
    $this->conditions = $conditions;
    $this->computations = $computations;
  }

  public function compile()
  {
    $conditions = $this->makeConditions($this->conditions);
    $computations = $this->makeComputations($this->computations);
    return function (Record $record) use ($conditions, $computations) {
      if ($conditions($record->data))
        $record->result = $computations($record->data, $record->result);
      return $record;
    };
  }
}
