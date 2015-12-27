<?php
namespace Liquid\Nodes\States;

use Liquid\Records\Collection;
use Liquid\Records\Record;

class ActiveState extends InitialState
{
  public function compileProcess()
  {
    return function (Collection $collection) {
      return $this->processor->process($collection);
    };
  }

  public function compilePush()
  {
    return function (Record $record) {
      foreach ($this->nexts as $node) {
        $node->collection->push($record);
      }
    };
  }

  public function compileHandle()
  {
    return function (MessageInterface $message) {
      $message->apply($this);
    };
  }

  public function compileBroadcast()
  {
    return function (MessageInterface $message) {
      foreach ($this->nexts as $node) {
        $node->handle($message);
      }
    };
  }
}
