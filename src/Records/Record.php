<?php

namespace Liquid\Records;

use Liquid\Nodes\BaseNode;

class Record
{
  use Traits\MergeTrait;

  protected static $history = [
    'result' => [],
    'checkpoint' => [],
  ];

  protected $label;

  protected $status;

  protected $data = [];

  protected $result = [];

  protected $memory = [];

  public function __construct(array $data = [], array $result = [], array $memory = [])
  {
    $this->label = 'record_' . uniqid();
    $this->data = $data;
    $this->result = $result;
    $this->memory = $memory;
    $this->status = false;
  }

  public static function history($index = null)
  {
    if ($index) {
      if (array_key_exists($index, self::$history)) {
        return self::$history[$index];
      } else {
        throw new \Exception("field {$index} not exists in record history");
      }
    } else {
      return self::$history;
    }
  }

  public static function forget()
  {
    self::$history = [
      'result' => [],
      'checkpoint' => [],
    ];
  }

  public function __clone()
  {
    $this->label = 'record_' . uniqid();
    // reset status and memory
    $this->status = false;
    $this->memory = [];
  }

  public function getLabel()
  {
    return (string)$this->label;
  }

  public function getData($key = null)
  {
    if ($key) {
      return isset($this->data[$key]) ? $this->data[$key] : null;
    } else {
      return (array)$this->data;
    }
  }

  public function getResult($key = null)
  {
    if ($key) {
      return isset($this->result[$key]) ? $this->result[$key] : null;
    } else {
      return (array)$this->result;
    }
  }

  public function setResult(array $value)
  {
    $this->result = $value;
  }

  public function getHistory($key = null)
  {
    if ($key) {
      return isset(self::$history[$key]) ? self::$history[$key] : null;
    } else {
      return (array)self::$history;
    }
  }

  public function getMemory($key = null)
  {
    if ($key) {
      return isset($this->memory[$key]) ? $this->memory[$key] : null;
    } else {
      return (array)$this->memory;
    }
  }

  public function setMemory(array $value)
  {
    $this->memory = $value;
  }

  public function getStatus()
  {
    return (boolean)$this->status;
  }

  public function setStatus($value)
  {
    $this->status = (boolean)$value;
  }

  public function fromHistory(BaseNode $node)
  {
    if (isset(self::$history['checkpoint'][$node->getName()])) {
      $checkpoint = self::$history['checkpoint'][$node->getName()];
      // load status and memory from history checkpoint
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
