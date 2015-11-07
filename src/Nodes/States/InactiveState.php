<?php
namespace Liquid\Nodes\States;

use Liquid\Messages\MessageInterface;

class InitialState implements StateInterface
{
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
