<?php
namespace Liquid\Units\Traits;

use Respect\Validation\Validator;

trait ValidationTrait
{
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
}
