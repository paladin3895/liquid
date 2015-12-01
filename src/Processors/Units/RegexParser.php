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

  public function __construct($key, $signature)
  {
    $this->key = $key;
    $this->signature = $signature;
  }

  public function compile()
  {
    $key = $this->key;
    $signature = $this->signature;
    return function (Record $record) use ($key, $signature) {
      foreach ($record->data as $label => &$value) {
        if ($label != $key) continue;
        if (preg_match($signature, $value, $matches)) {
          $value = end($matches);
        }
      }
      return $record;
    };
  }
}
