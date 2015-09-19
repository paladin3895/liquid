<?php

class RegexParser extends BaseUnit
{
  protected $signature = '';

  public function __construct($signature) {
    parent::__construct($signature);
    $this->signature = (string)$signature;
  }

  public function process(array $record) {
    $output = [];
    $matches = [];
    foreach ($record as $key => $value) {
      if (preg_match($this->signature, $value, $matches))
        $output[$key] = end($matches);
    }
    return $output;
  }
}
