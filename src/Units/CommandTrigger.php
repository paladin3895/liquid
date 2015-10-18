<?php

namespace Liquid\Units;

use Liquid\Messages\Commands\DisplayCommand;
use ReflectionClass;
use Exception;

class CommandTrigger extends BaseUnit implements FormatInterface
{
  protected $command;
  protected $conditions;

  protected $namespace = "Liquid\Messages\Commands\\";

  public function __construct(array $conditions, array $receivers, $command, $name = null)
  {
    parent::__construct($name);
    $reflection = new ReflectionClass($this->namespace . $command);
    if (!$reflection->isInstantiable()) throw new Exception('invalid command');
    $this->command = $reflection->newInstance($receivers);
    $this->conditions = $conditions;
  }

  public function process(array $record)
  {
    foreach ($this->conditions as $key => $value) {
      if (array_key_exists($key, $record) && $record[$key] == $value)
        $this->processor->trigger($this->command);
    }
    return $record;
  }

  public static function getFormat()
  {
    return [
      'conditions' => 'array',
      'receivers' => 'array',
      'command' => 'string',
    ];
  }
}
