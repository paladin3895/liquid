<?php
namespace Liquid\Messages;

use Liquid\Processors\MessengerInterface;
use Liquid\Nodes\BaseNode;

interface MessageInterface
{
  public function apply($target);
}
