<?php
require_once('MessengerInterface.php');

class ExecutiveProcessor extends ParallelProcessor implements MessengerInterface
{
  public function process(array $data)
  {
    $output = [];
    foreach ($data as $label => $record) {
      foreach ($this->processUnits as $unit) {
        $unit->setLabel($label);
        $record = $unit->process($record);
      }
      $output = array_merge($output, $record);
    }
    return $output;
  }

  public function handle(MessageInterface $message)
  {
    if (!$message->isReceiver($this)) return false;
    if (!$this->_validate($message->conditions())) return false;
    foreach ($message->actions() as $object => $actions) {
      $actions = explode('|', $actions);
      switch ($object) {
        case 'node':
          $object = $this->node; break;
        case 'processor':
          $object = $this; break;
      }
      foreach ($actions as $action) {
        if (!is_callable([$object, $action])) continue;
        call_user_func([$object, $action]);
      }
    }
  }

  public function trigger(MessageInterface $message)
  {
    switch ($message->triggerType()) {
      case 'broadcast': return $this->node->broadcastMessage($message);
      case 'forward' : return $this->node->forwardMessage($message);
      case 'backward' : return $this->node->backwardMessage($message);
      default: return;
    }
  }

  protected function _validate(array $conditions)
  {
    return true;
  }
}
