<?php
namespace Liquid\Processors\Units;

use Liquid\Records\Record;
use Liquid\Helpers\Condition;
use Liquid\Helpers\Message;

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

  public function compile()
  {
    $conditions = Condition::make($config['conditions']);

    $message = Message::make($config['message'], $config['receivers']);

    return function (Record $record) use ($conditions, $message) {
      if ($conditions($record->data)) {
        $this->trigger($message);
      }
      return $record;
    };
  }
}
