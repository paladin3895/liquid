<?php

namespace Liquid\Builders\Traits;

trait SingletonTrait
{
  protected static $instance;

  private function __construct()
  {
    
  }

  public static function getInstance()
  {
    if ($instance) return $instance;
    self::$instance = new self();
  }
}
