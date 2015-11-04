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
    if (!self::$instance) self::$instance = new self();
    return self::$instance;
  }
}
