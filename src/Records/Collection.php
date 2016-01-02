<?php

namespace Liquid\Records;

use Liquid\Records\Record;
use IteratorAggregate;
use SplDoublyLinkedList;

class Collection implements IteratorAggregate
{
  use Traits\MergeTrait;

  protected $container;

  public function __construct()
  {
    $this->container = new SplDoublyLinkedList;
  }

  public function __call($method , array $arguments)
  {
    return call_user_func_array([$this->container, $method], $arguments);
  }

  public function getIterator()
  {
    return $this->container;
  }

  public function merge()
  {
    $data = [];
    $result = [];
    foreach ($this->container as $record) {
      $data = array_merge($data, $record->data);
      $result = $this->_conditionedMerge($result, $record->result);
    }
    foreach ($result as $key => $value) {
      $data = $this->_conditionedMerge($data, $result);
    }
    return new Record($data, $result);
  }
}
