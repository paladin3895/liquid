<?php

namespace Liquid\Messages\Commands;

use Liquid\Nodes\BaseNode;

class TerminateCommand extends BaseCommand
{
  public function apply($target)
  {
    if (!($target instanceof BaseNode)) return;
    if (!in_array($target->getName(), $this->receivers)) return;
    $target->terminate();
  }
}
