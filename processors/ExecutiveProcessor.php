<?php

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

  }

  public function trigger(MessageInterface $message)
  {

  }
}
