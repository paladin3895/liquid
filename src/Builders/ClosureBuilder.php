<?php

namespace Liquid\Builders;

use Exception;

class ClosureBuilder implements BuilderInterface
{
  use Traits\ValidationTrait, Traits\ExpressionTrait, Traits\FormatTrait;

  protected $format = [
    'class' => 'string',
    'conditions' => 'array',
    'computations' => 'array',
  ];

  /*
   * array format:
   * [
   *   'conditions' => [
   *     'integer' => 'int,greater:0,lesser:10,between:0|10',
   *     ...
   *   ],
   *   'computations' => [
   *     'count' => '(number + 1) * 2',
   *     'number' => 'number + 1',
   *   ]
   */

  public function make(array $config)
  {
    $config = $this->_format($config);

    $conditions = $this->makeConditions($config['conditions']);
    $computations = $this->makeComputations($config['computations']);
    return function (array $record, array $result) use ($conditions, $computations) {
      if ($conditions($record)) return $computations($record, $result);
      return $result;
    };
  }
}
