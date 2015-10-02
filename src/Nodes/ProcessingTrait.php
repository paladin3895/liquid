<?php

namespace Liquid\Nodes;

use Liquid\Processors\BaseProcessor;

trait ProcessingTrait
{
	protected $processor;

  public function bind(BaseProcessor $processor)
  {
    $this->processor = $processor;
    $processor->bind($this);
    $this->status |= self::STATUS_ACTIVE;
  }

  public function process()
  {
    $this->_pull();
    if ($this->status & self::STATUS_ACTIVE)
      $this->output = $this->processor->process($this->input);
    else
      $this->output = $this->input;
    // $this->_push();
  }
}
