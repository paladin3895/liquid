<?php

namespace Liquid\Records;

use Liquid\Nodes\BaseNode;

class Record
{
  public $label;

  public $data = [];

  public $result = [];

  protected $history = [];

  public function __construct(array $data)
  {
    $this->label = 'record_' . uniqid();
    $this->data = $data;
  }

  public function __clone()
  {
    $this->label = 'record_' . uniqid();
  }

  public function toHistory(BaseNode $node)
  {
    $this->history[$this->label] = $node->getName();
  }
}
