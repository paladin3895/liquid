<?php

namespace Liquid\Records\Collection;

use Liquid\Records\Record;
use IteratorAggregate;
use SplObjectStorage;

class Collection implements IteratorAggregate
{
  protected $container;

  public function __construct()
  {
    $this->container = new SplObjectStorage;
  }

  public function attach(Record $record)
  {
    $this->container->attach($record);
  }

  public function detach(Record $record)
  {
    $this->container->detach($record);
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
