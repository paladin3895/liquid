<?php
namespace Liquid\Helpers;

class Messenger
{
  public function make()
  {
    $reflection = new ReflectionClass($this->namespace . $command);
    if (!$reflection->isInstantiable())
      throw new Exception('invalid command in {__CLASS__} at {__FILE__}, line {__LINE__}');
    $command = $reflection->newInstance($receivers);
  }
}
