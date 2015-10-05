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
      $this->processor->process($this->getInput(), $this->getResult());
    else
      throw new \Exception('node ' . $this->name . ' doesnt have a processor');
    // $this->_push();
  }
}
