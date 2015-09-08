<?php

class RegexParser implements ProcessUnitInterface
{
  protected $signature = '';

  public function __construct($signature) {
    $this->signature = (string)$signature;
  }

  public function process(array $input) {
    $output = [];
    $matches = [];
    foreach ($input as $value) {
      if (is_array($value))
        $this->process($value);
      elseif (preg_match($this->signature, $value, $matches))
        $output[] = end($matches);
    }
    return $output;
  }
}
