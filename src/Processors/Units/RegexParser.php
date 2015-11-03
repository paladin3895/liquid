<?php
namespace Liquid\Processors\Units;

use Liquid\Records\Record;

class RegexParser implements ProcessUnitInterface
{
  public static function getFormat()
  {
    return [
      'key' => 'string',
      'signature' => 'string',
    ];
  }

  public static function validate(array $config)
  {
    if (!isset($config['key'])) return false;
    if (!is_scalar($config['key'])) return false;

    if (!isset($config['signature'])) return false;
    if (preg_match($config['signature'], '') === false) return false;

    return true;
  }

  public static function compile(array $config)
  {
    return function (Record $record) use ($config) {
      foreach ($record->data as $key => &$value) {
        if ($key != $config['key']) continue;
        if (preg_match($config['signature'], $value, $matches)) {
          $value = end($matches);
        }
      }
      return $record;
    };
  }
}
