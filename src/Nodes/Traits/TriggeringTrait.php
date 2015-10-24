<?php

namespace Liquid\Nodes\Traits;

use Liquid\Nodes\BaseNode;
use Liquid\Messages\MessageInterface;
use Liquid\Processors\MessengerInterface;
use Liquid\Messages\Commands\BaseCommand;

trait TriggeringTrait
{
  public function handleMessage(MessageInterface $message)
  {
    if ($message instanceof BaseCommand) {
      $message->apply($this);
      $this->forwardMessage($message);
    }
    elseif ($message instanceof Signal) {
      $message->apply($this->processor);
      $this->broadcastMessage($message);
    }
    elseif ($message instanceof Feedback) {
      $message->apply($this);
      $this->backwardMessage($message);
    }

    $message->mark($this);
  }

  public function broadcastMessage(MessageInterface $message)
  {
    foreach ($this->previouses as $node) {
      if ($message->isMarked($node)) continue;
      $node->handleMessage($message);
      $node->broadcastMessage($message);
    }

    foreach ($this->nexts as $node) {
      if ($message->isMarked($node)) continue;
      $node->handleMessage($message);
      $node->broadcastMessage($message);
    }
  }

  public function forwardMessage(MessageInterface $message)
  {
    foreach ($this->nexts as $node) {
      if ($message->isMarked($node)) continue;
      $node->handleMessage($message);
      $node->forwardMessage($message);
    }
  }

  public function backwardMessage(MessageInterface $message)
  {
    foreach ($this->previouses as $node) {
      if ($message->isMarked($node)) continue;
      $node->handleMessage($message);
      $node->backwardMessage($message);
    }
  }
}
