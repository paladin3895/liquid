<?php
namespace Liquid\Processors\Units;

use Liquid\Records\Record;
use Liquid\Helpers\Condition;
use Liquid\Helpers\Message;

class MessageTrigger implements ProcessUnitInterface
{
  protected $conditions;

  protected $message;

  public static function getFormat()
  {
    return [
      'receivers' => ["name"],
      'conditions' => ["key" => "conditions"],
      'message' => 'class',
    ];
  }

  public static function validate(array $config)
  {
    if (!isset($config['receivers'])) return false;
    if (!is_array($config['receivers'])) return false;

    if (!isset($config['conditions'])) return false;
    if (!is_array($config['conditions'])) return false;

    if (!isset($config['message'])) return false;
    if (!is_scalar($config['message'])) return false;

    return true;
  }

  public function __construct(array $receivers, array $conditions, $message)
  {
    $this->conditions = Condition::make($conditions);
    $this->message = Message::make($message, $receivers);
  }

  public function compile()
  {
    $conditions = $this->conditions;
    $message = $this->message;

    return function (Record $record) use ($conditions, $message) {
      if ($conditions($record->data)) {
        $this->trigger($message);
      }
      return $record;
    };
  }
}
