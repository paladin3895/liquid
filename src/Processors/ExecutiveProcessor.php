<?php
namespace Liquid\Processors;

use Liquid\Processors\MergingProcessor;
use Liquid\Processors\MessengerInterface;
use Liquid\Messages\MessageInterface;

use Liquid\Messages\Command;
use Liquid\Messages\Signal;
use Liquid\Messages\Instruction;

class ExecutiveProcessor extends MergingProcessor implements MessengerInterface
{
  public static $alias = 'executive';

  public function trigger(MessageInterface $message)
  {
    $this->node->handleMessage($message);
  }
}
