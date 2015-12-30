<?php

namespace Liquid\Nodes\Traits;

use Liquid\Nodes\BaseNode;
use Liquid\Registry;

trait RegisteringTrait
{
	protected $registry;

  public function terminate()
  {
    $this->unregister();
    foreach ($this->nexts as $node) {
      foreach ($node->previouses as $previous_node) {
        if ($previous_node->status & self::STATUS_ALIVE) continue 2;
      }
      $node->terminate();
    }
  }

  public function register(Registry $registry)
  {
    $this->registry = $registry;
    $this->registry->attach($this);
    $this->status |= self::STATUS_INITIALIZED;
  }

  public function unregister()
  {
    $this->registry->detach($this);
		$this->registry = null;
    $this->status &= ~self::STATUS_INITIALIZED;
  }

	public function getRegistry()
	{
		return $this->registry;
	}
}
