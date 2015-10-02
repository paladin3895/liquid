<?php
namespace Liquid\Processors;

use Liquid\Messages\MessageInterface;

interface MessengerInterface
{
  public function trigger(MessageInterface $message);
}
