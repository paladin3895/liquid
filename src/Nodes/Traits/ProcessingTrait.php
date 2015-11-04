<?php

namespace Liquid\Nodes\Traits;

use Liquid\Nodes\BaseNode;
use Liquid\Processors\BaseProcessor;

trait ProcessingTrait
{
	protected $processor;

	protected $collection;

  public function bind(BaseProcessor $processor)
  {
    $this->processor = $processor;
    $processor->bind($this);
    $this->status |= self::STATUS_ACTIVE;
  }

  public function process()
  {
    if ($this->status & self::STATUS_ACTIVE)
      $this->_push($this->processor->process($this->collection));
    else
      throw new \Exception('node ' . $this->name . ' doesnt have a processor');
  }
}
