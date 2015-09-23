<?php
require_once('ProcessUnitInterface.php');

abstract class BaseUnit implements ProcessUnitInterface
{
  protected $recordLabel = '';

  protected $processor;

  public function __construct()
  {

  }

  public function setLabel($label)
  {
    $this->recordLabel = $label;
  }

  public function getLabel()
  {
    return $this->recordLabel;
  }

  public function bind(Processor $processor)
  {
    $this->processor = $processor;
  }

  abstract public function process(array $record);
}
