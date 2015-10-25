<?php

namespace Liquid\Nodes\Traits;

use Liquid\Nodes\BaseNode;

trait ConnectingTrait
{
  // create a piping stucture in which
  // output of one node is input of the nexts
  public function forward(BaseNode $next)
  {
    if (!$this->nexts->contains($next)) $this->nexts->attach($next);
    if (!$next->previouses->contains($this)) $next->previouses->attach($this);
    $this->_update();
  }

  protected function _update()
  {
    foreach ($this->previouses as $previous) {
      if ($previous->depth < $this->depth) continue;
      $this->depth = $previous->depth + 1;
    }
    foreach ($this->nexts as $next) {
      $next->_update();
    }
  }

  protected function _push(Record $record)
  {
    foreach ($this->nexts as $node) {
      $node->records->attach(clone $this->record);
    }
  }
}
