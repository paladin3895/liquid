<?php

namespace Liquid\Nodes\Traits;

use Liquid\Nodes\BaseNode;
use Liquid\Records\Record;

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
}
