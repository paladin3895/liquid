<?php

namespace Liquid\Records\Collection;

use Liquid\Records\Record;
use IteratorAggregate;
use SplDoublyLinkedList;

class Collection implements IteratorAggregate
{
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
    foreach ($this->container as $record) {
      $data = array_merge($data, $record->data);
    }
    return new Record($data);
  }
}
