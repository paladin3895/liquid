<?php

namespace Liquid\Builders;

class ClosureBuilder implements BuilderInterface
{
  use ValidationTrait, ExpressionTrait;

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
    if (!$config) return;

    $conditions = $this->makeConditions($config['conditions']);
    $computations = $this->makeComputations($config['computations']);
    return function (array $record, array $result) use ($conditions, $computations) {
      if ($conditions($record)) return $computations($record, $result);
      return $result;
    };
  }

  protected function _format(array $config)
  {
    $output = [];
    foreach ($this->format as $key => $type) {
      if (!array_key_exists($key, $config)) return false;
      if (gettype($config[$key]) != $type) return false;
      $output[$key] = $config[$key];
    }
    return $output;
  }
}
