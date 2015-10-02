<?php
namespace Liquid\Processors;

use Liquid\Processors\ParallelProcessor;
use Liquid\Processors\MessengerInterface;
use Liquid\Messages\MessageInterface;

use Liquid\Messages\Command;
use Liquid\Messages\Signal;
use Liquid\Messages\Instruction;

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

  public function trigger(MessageInterface $message)
  {
    $this->node->handleMessage($message);
  }
}
