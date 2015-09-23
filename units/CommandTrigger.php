<?php
require_once('ProcessUnitInterface.php');

class CommandTrigger implements ProcessUnitInterface
{
  protected $command;
  protected $conditions;

  public function __construct(Command $command, array $conditions)
  {
    $this->command = $command;
    $this->conditions = $conditions;
  }

  public function process(array $record)
  {
    foreach ($this->conditions as $key => $value) {
      if (array_key_exists($key, $record) && $record[$key] == $value)
        $this->processor->trigger($command);
    }
    return $record;
  }
}
