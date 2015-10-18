<?php

namespace Liquid\Nodes;

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

  protected function _push()
  {
    foreach ($this->nexts as $node) {
      $node->input[$this->name] = $this->output;
    }
  }

  protected function _pull()
  {
    // if ($this->previouses->count() == 0) $this->input['void'] = ['placeholder'];
    foreach ($this->previouses as $node) {
      if (empty($node->output)) continue;
      $this->input[$node->name] = $node->output;
    }
  }
}
