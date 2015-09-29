<?php
namespace Liquid\Messages;

use Liquid\Messages\MessageInterface;
use Liquid\Processors\MessengerInterface;
use Liquid\Nodes\BaseNode;
use SplObjectStorage;

class Command implements MessageInterface
{
  protected $nodes;

  protected $actions;
  protected $receivers;

  public function mark(BaseNode $node)
  {
    if (!$this->isMarked($node)) $this->nodes->attach($node);
  }

  public function isMarked(BaseNode $node)
  {
    return $this->nodes->contains($node);
  }

  public function __construct(array $receivers, array $actions)
  {
    $this->nodes = new SplObjectStorage;
    $this->receivers = $receivers;
    $this->actions = $actions;
  }

  public function conditions()
  {
    return [];
  }

  public function actions()
  {
    return $this->actions;
  }

  public function isReceiver(MessengerInterface $messenger)
  {
    return in_array($messenger->getNode()->getName(), $this->receivers);
  }

  public function triggerType()
  {
    return 'forward';
  }
}
