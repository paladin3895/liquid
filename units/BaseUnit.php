<?php
require_once('ProcessUnitInterface.php');

abstract class BaseUnit implements ProcessUnitInterface
{
  protected $recordLabel = '';

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

  abstract public function process(array $record);
}
