<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as Expression;

function makeExpression($key, $expression)
{
  $parameters = [];
  if (preg_match_all('#[a-zA-Z]+#', $expression, $matches))
    $parameters = $matches[0];
  return function (array $result) use ($key, $parameters, $expression) {
    foreach ($parameters as $parameter) {
      if (!isset($result[$parameter])) $result[$parameter] = 0;
    }
    if (!isset($result[$key])) $result[$key] = 0;
    $result[$key] = (new Expression)->evaluate($expression, $result);
    return $result;
  };
}

$operator = makeExpression('foo', 'foo + bar * 2');
var_dump($operator(['bar' => 1]));
