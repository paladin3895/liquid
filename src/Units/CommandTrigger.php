<?php

namespace Liquid\Units;

use Liquid\Messages\Command;

class CommandTrigger extends BaseUnit implements ProcessUnitInterface
{
  protected $command;
  protected $conditions;

  public function __construct(array $conditions, array $receivers, array $actions, $name = null)
  {
    parent::__construct($name);
    $this->command = new Command($receivers, $actions);
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
      'actions' => 'array',
      'name' => 'string',
    ];
  }
}
