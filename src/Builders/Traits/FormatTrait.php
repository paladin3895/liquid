<?php

namespace Liquid\Builders\Traits;

trait FormatTrait
{
  protected function _format(array $config)
  {
    $output = [];
    foreach ($this->format as $key => $type) {
      if (!array_key_exists($key, $config))
          throw new Exception("invalid config field {$key}");

      if (gettype($config[$key]) != $type)
          throw new Exception("invalid data type of config field {$key} provided");

      $output[$key] = $config[$key];

    }
    return $output;
  }
}
