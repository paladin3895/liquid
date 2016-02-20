<?php

namespace Liquid\Records;

use Liquid\Nodes\BaseNode;

class Record
{
  use Traits\MergeTrait;

  public static $history = [
    'result' => [],
    'checkpoint' => [],
  ];

  public $label;

  public $status;

  public $data = [];

  public $result = [];

  public $memory = [];

  public function __construct(array $data = [], array $result = [])
  {
    $this->label = 'record_' . uniqid();
    $this->data = $data;
    $this->status = false;
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
      $checkpoint = self::$history['checkpoint'][$node->getName()];
      $this->status = isset($checkpoint['status']) ? $checkpoint['status'] : false;
      $this->memory = isset($checkpoint['memory']) ? $checkpoint['memory'] : [];
    }
  }

  public function toHistory(BaseNode $node)
  {
    self::$history['checkpoint'][$node->getName()] = [
        'status' => (bool)$this->status,
        'memory' => $this->memory,
        'result' => $this->result,
    ];
    self::$history['result'] = $this->_conditionedMerge(self::$history['result'], $this->result);
  }
}
