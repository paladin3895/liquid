<?php

namespace Liquid\Nodes;

use Liquid\Registry;

trait RegisteringTrait
{
	protected $registry;

  public function attach(Registry $registry)
  {
    $registry->attach($this);
    $this->registry = $registry;
  }

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

  public function initialize(Registry $registry)
  {
    $this->register($registry);
    foreach ($this->nexts as $node) {
      $node->initialize($registry);
    }
  }

  public function register(Registry $registry)
  {
    $this->registry = $registry;
    if ($this->registry->hasRegistered($this)) return;
    $this->registry->register($this);
    $this->status |= self::STATUS_INITIALIZED;
  }

  public function unregister()
  {
    if (!$this->registry->hasRegistered($this)) return;
    $this->registry->unregister($this);
    $this->status &= ~self::STATUS_INITIALIZED;
  }

	public function getRegistry()
	{
		return $this->registry;
	}

	public function setResult(array $result)
	{
		$this->registry->setResult($result);
	}

	public function getResult()
	{
		return $this->registry->getResult();
	}
}
