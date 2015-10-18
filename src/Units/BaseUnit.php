<?php

namespace Liquid\Units;

use Liquid\Processors\BaseProcessor;

abstract class BaseUnit implements ProcessUnitInterface
{
  protected $name;

  protected $recordLabel;

  protected $processor;

  public function __construct($name = null)
  {
    $this->name = isset($name) ? (string)$name : uniqid('unit_');
  }

  public function getName()
  {
    return $this->name;
  }

  public function setLabel($label)
  {
    $this->recordLabel = $label;
  }

  public function getLabel()
  {
    return $this->recordLabel;
  }

  public function stack(BaseProcessor $processor)
  {
    $this->processor = $processor;
  }

  abstract public function process(array $record);
}
