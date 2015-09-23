<?php

interface MessengerInterface
{
  public function handle(MessageInterface $message);

  public function trigger(MessageInterface $message);
}
