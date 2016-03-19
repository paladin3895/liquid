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

  public static function makeExpression($expression)
  {
    $parameters = [];
    if (preg_match_all('#\$\{([a-zA-Z0-9\_]+)\}#', $expression, $matches)) {
      $placeholders = $matches[0];
      $parameters = $matches[1];
    }

    // prepare mapping for parameters with unique random key
    // initialize collection of values
    $mapping = [];
    $collection = [];
    foreach ($parameters as $index => $parameter) {
      $key = str_shuffle(implode('', range('a', 'z')));
      $mapping[$parameter] = $key;
      $collection[$key] = 0;

      $expression = str_replace($placeholders[$index], $mapping[$parameter], $expression);
    }

    return function (array $record) use ($expression, $mapping, $collection) {
      foreach ($record as $key => $value) {
        if (array_key_exists($key, $mapping)) {
          $collection[$mapping[$key]] = $value;
        }
      }
      return (new ExpressionLanguage)->evaluate($expression, $collection);
    };
  }
}
