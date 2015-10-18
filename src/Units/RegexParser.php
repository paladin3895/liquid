<?php

namespace Liquid\Units;

class RegexParser extends BaseUnit implements FormatInterface
{
  protected $signature;

  protected $key;

  public function __construct($key, $signature, $name = null) {
    parent::__construct($name);
    $this->signature = (string)$signature;
    $this->key = (string)$key;
  }

  public function process(array $record) {
    $output = [];
    $matches = [];
    foreach ($record as $key => $value) {
      if ($key != $this->key) continue;
      if (preg_match($this->signature, $value, $matches))
        $output[$key] = end($matches);
    }
    return $output;
  }

  public static function getFormat()
  {
    return [
      'key' => 'string',
      'signature' => 'string',
    ];
  }
}
