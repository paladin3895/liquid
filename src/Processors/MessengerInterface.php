<?php
namespace Liquid\Processors\MessengerInterface;

use Liquid\Messages\MessageInterface;

interface MessengerInterface
{
  public function handle(MessageInterface $message);

  public function trigger(MessageInterface $message);

}
