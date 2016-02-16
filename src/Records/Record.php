<?php

namespace Liquid\Records;

use Liquid\Nodes\BaseNode;

class Record
{
  public static $history = [];

  public $label;

  public $status;

  public $data = [];

  public $result = [];

  public $memory = [];

  public function __construct(array $data = [], array $history = [])
  {
    $this->label = 'record_' . uniqid();
    $this->data = $data;
    $this->status = false;
    self::$history += $history;
  }

  public function __clone()
  {
    $this->label = 'record_' . uniqid();
    $this->status = false;
    $this->memory = [];
  }

  public function fromHistory(BaseNode $node)
  {
    if (isset(self::$history[$node->getName()])) {
      $checkpoint = self::$history[$node->getName()];
      $this->status = isset($checkpoint['status']) ? $checkpoint['status'] : false;
      $this->memory = isset($checkpoint['memory']) ? $checkpoint['memory'] : [];
    }
  }

  public function toHistory(BaseNode $node)
  {
    self::$history[$node->getName()] = [
        'status' => (bool)$this->status,
        'memory' => $this->memory,
        'result' => $this->result,
    ];
  }
}
