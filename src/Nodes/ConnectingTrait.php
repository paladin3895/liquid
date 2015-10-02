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
    $next->depth = ($next->depth > $this->depth) ? $next->depth : ($this->depth + 1);
  }

  public function backward(BaseNode $previous)
  {
    if (!$this->previouses->contains($previous)) $this->previouses->attach($previous);
    if (!$previous->nexts->contains($this)) $previous->nexts->attach($this);
    $this->depth = ($this->depth > $previous->depth) ? $this->depth : ($previous->depth + 1);
  }

  public function hub(array $previouses)
  {
    foreach ($previouses as $node) {
      if (!($node instanceof BaseNode)) throw new Exception('invalid node type');
      $this->backward($node);
    }
  }

  public function split(array $nexts)
  {
    foreach ($nexts as $node) {
      if (!($node instanceof BaseNode)) throw new Exception('invalid node type');
      $this->forward($node);
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
    if ($this->previouses->count() == 0) $this->input['void'] = ['placeholder'];
    foreach ($this->previouses as $node) {
      if (empty($node->output)) continue;
      $this->input[$node->name] = $node->output;
    }
  }
}
