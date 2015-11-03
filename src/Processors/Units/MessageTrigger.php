<?php
namespace Liquid\Processors\Units;

use Liquid\Records\Record;

class MessageTrigger implements ProcessUnitInterface
{
  public static function getFormat()
  {
    return [
      'conditions' => ["key" => "conditions"],
      'receivers' => ["name"],
      'message' => 'class',
    ];
  }

  public static function validate(array $config)
  {
    if (!isset($config['conditions'])) return false;
    if (!is_array($config['conditions'])) return false;

    if (!isset($config['receivers'])) return false;
    if (!is_array($config['receivers'])) return false;

    if (!isset($config['message'])) return false;
    if (!is_scalar($config['message'])) return false;

    return true;
  }

  public static function compile(array $config)
  {
    $conditions = Validator::make($config['conditions']);

    $message = Message::make($config['message'], $config['receivers']);

    return function (Record $record) use ($conditions, $message) {
      if ($conditions($record->data)) {
        $this->trigger($message);
      }
      return $record;
    };
  }
}
