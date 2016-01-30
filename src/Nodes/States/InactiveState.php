<?php
namespace Liquid\Nodes\States;

use Liquid\Records\Collection;
use Liquid\Records\Record;

use Liquid\Interfaces\MessageInterface;
use Liquid\Interfaces\StateInterface;

class InactiveState implements StateInterface
{
  public function compileProcess()
  {
    return function (Collection $collection) {
      // do nothing
    };
  }

  public function compilePush()
  {
    return function (Record $record) {
      // do nothing
    };
  }

  public function compileHandle()
  {
    return function (MessageInterface $message) {
      // do nothing
    };
  }

  public function compileBroadcast()
  {
    return function (MessageInterface $message) {
      // do nothing
    };
  }
}
