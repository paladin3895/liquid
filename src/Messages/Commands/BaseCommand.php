<?php
namespace Liquid\Messages\Commands;

use Liquid\Messages\MessageInterface;
use Liquid\Nodes\BaseNode;
use SplObjectStorage;

abstract class BaseCommand implements MessageInterface
{
  protected $nodes;

  protected $receivers;

  public function __construct(array $receivers)
  {
    $this->nodes = new SplObjectStorage;
    $this->receivers = $receivers;
  }

  public function mark(BaseNode $node)
  {
    if (!$this->isMarked($node)) $this->nodes->attach($node);
  }

  public function isMarked(BaseNode $node)
  {
    return $this->nodes->contains($node);
  }

  abstract public function apply($target);
}
