<?php
namespace Liquid\Nodes\States;

use Liquid\Records\Collection;
use Liquid\Records\Record;

use Liquid\Interfaces\MessageInterface;
use Liquid\Interfaces\StateInterface;

class InitialState implements StateInterface
{
  public function compileProcess()
  {
    return function () {
      // do nothing
    };
  }

  public function compilePush()
  {
    return function () {
      // do nothing
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
