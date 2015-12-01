<?php
namespace Liquid\Helpers;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Expression
{
  public static function make(array $computations)
  {
    $computator = new ExpressionLanguage;

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
}
