<?php

namespace Liquid\Builders;

use Respect\Validation\Validator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage as Expression;

class ClosureBuilder implements BuilderInterface
{
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

  public function makeConditions(array $conditions)
  {
    $closures = [];
    foreach ($conditions as $key => $evaluations) {
      $closures[] = $this->makeEvaluations($key, $evaluations);
    }
    return function(array $record) use ($closures) {
      foreach ($closures as $closure) {
        if (!$closure($record)) return false;
      }
      return true;
    };
  }

  public function makeEvaluations($key, $evaluations)
  {
    $evaluations = explode(',', $evaluations);
    $validator = new Validator;
    foreach ($evaluations as $evaluation) {
      $evaluation = explode(':', $evaluation);
      $type = $evaluation[0];
      $operands = explode('|', $evaluation[1]);
      $validator = call_user_func_array([$validator, $type], $operands);
    }
    return function (array $record) use ($key, $validator) {
      return (new Validator)->key($key, $validator)->validate($record);
    };
  }

  public function makeComputations(array $computations)
  {

    $computator = new Expression;

    return function (array $record, array $result) use ($computations, $computator) {

      $collection = array_merge($result, $record);

      foreach ($computations as $key => $expression) {
        $parameters = [];
        if (preg_match_all('#[a-zA-Z]+#', $expression, $matches))
          $parameters = $matches[0];

        foreach ($parameters as $parameter) {
          if (!isset($collection[$parameter])) $collection[$parameter] = 0;
        }
        if (!isset($result[$key])) $result[$key] = 0;

        $result[$key] = $computator->evaluate($expression, $collection);
      }
      return $result;
    };
  }

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
