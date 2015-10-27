<?php

namespace Liquid\Units;

use Liquid\Messages\Commands\DisplayCommand;
use Respect\Validation\Validator;
use ReflectionClass;
use Exception;

class CommandTrigger extends BaseUnit implements FormatInterface
{
  use Traits\ValidationTrait;

  protected $command;
  protected $conditions;

  protected $namespace = "Liquid\Messages\Commands\\";

  public function __construct(array $conditions, array $receivers, $command, $name = null)
  {
    parent::__construct($name);
    $reflection = new ReflectionClass($this->namespace . $command);
    if (!$reflection->isInstantiable())
      throw new Exception('invalid command in {__CLASS__} at {__FILE__}, line {__LINE__}');
    $this->command = $reflection->newInstance($receivers);
    $this->conditions = $conditions;
  }

  public function compile()
  {
    $conditions = $this->makeConditions($this->conditions);
    return function (array $record, array $result) use ($conditions) {
      if ($conditions($record)) $this->processor->trigger($this->command);
      return $record;
    };
  }

  public function process(array $record)
  {
    $conditions = $this->makeConditions($this->conditions);
    if ($conditions($record)) $this->processor->trigger($this->command);
    return $record;
  }

  public static function getFormat()
  {

  }
}


namespace Liquid\Units;

use Liquid\Records\Record;

class Messenger implements ProcessUnitInterface
{
  public static function getFormat()
  {
    return [
      'conditions' => ["key" => "conditions"],
      'receivers' => ["name"],
      'command' => 'command class',
    ];
  }

  public static function compile(array $conditions, array $receivers, $message)
  {
    $conditions = Validator::make($conditions);

    $message = Message::make($message);

    $reflection = new ReflectionClass($this->namespace . $command);
    if (!$reflection->isInstantiable())
      throw new Exception('invalid command in {__CLASS__} at {__FILE__}, line {__LINE__}');
    $command = $reflection->newInstance($receivers);

    return function (Record $record) use ($conditions, $message) {
      if ($conditions($record->data)) {
        $message->broadcast();
      }
      return $record;
    };
  }
}
