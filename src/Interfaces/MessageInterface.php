<?php
namespace Liquid\Interfaces;

use Liquid\Nodes\BaseNode;

interface MessageInterface
{
  public function apply(BaseNode $target);
}
