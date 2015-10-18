<?php

namespace Liquid\Units;

abstract class Closure implements FormatInterface
{
  public static function getFormat()
  {
    return [
      'conditions' => 'array',
      'computations' => 'array',
    ];
  }
}
