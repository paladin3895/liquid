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
  public function trigger(MessageInterface $message)
  {
    $this->node->handleMessage($message);
  }
}
