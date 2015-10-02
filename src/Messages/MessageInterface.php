<?php
namespace Liquid\Messages;

use Liquid\Processors\MessengerInterface;
use Liquid\Nodes\BaseNode;

interface MessageInterface
{
  public function mark(BaseNode $node);

  public function isMarked(BaseNode $node);

  public function apply($target);
}
