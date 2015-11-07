<?php
namespace Liquid\Nodes\States;

use Liquid\Records\Collection;
use Liquid\Records\Record;
use Liquid\Messages\MessageInterface;

class InitialState implements StateInterface
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
        $node->collection->push(clone $record);
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
