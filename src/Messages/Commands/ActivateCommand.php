<?php

namespace Liquid\Messages\Commands;

use Liquid\Nodes\BaseNode;
use Liquid\Nodes\States\ActiveState;

class ActivateCommand extends BaseCommand
{
  public function apply($target)
  {
    if (!($target instanceof BaseNode)) return;
    if (!in_array($target->getName(), $this->receivers)) return;
    $target->change(new ActiveState);
  }
}
