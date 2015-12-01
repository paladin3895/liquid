<?php
namespace Liquid\Helpers;

class Message
{
  public static $namespace = 'Liquid\\\Messages';

  public static function make($message)
  {
    if (preg_match('#Command$#', $message)) {
      $this->namespace .= '\\\Commands';
    }

    $reflection = new ReflectionClass($this->namespace . '\\' . $message);
    if (!$reflection->isInstantiable())
      throw new Exception('invalid command in {__CLASS__} at {__FILE__}, line {__LINE__}');
    $message = $reflection->newInstance($receivers);
    return $message;
  }
}
