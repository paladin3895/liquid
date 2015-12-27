<?php

namespace Liquid\Records;

use Liquid\Nodes\BaseNode;

class Record
{
  public $label;

  public $data = [];

  public $result = [];

  public $status = false;

  protected $history = [];

  public function __construct(array $data = null, array $result = null)
  {
    $this->label = 'record_' . uniqid();
    $this->data = $data ? : [];
    $this->result = $result ? : [];
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
