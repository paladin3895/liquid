<?php

namespace Liquid\Units;

class ElementPicker extends BaseUnit
{

  protected $elements = [];

  public static function getFormat()
  {
    return [
      'elements' => 'array',
      'name' => 'string',
    ];
  }

  public function __construct(array $elements, $name = null)
  {
    parent::__construct($name);
    $this->elements = $elements;
  }

  public function process(array $record)
  {
    $output = [];
    foreach ($record as $key => $value) {
      if (!in_array($key, $this->elements)) continue;
      $output[$key] = $value;
    }
    return $output;
  }
}
