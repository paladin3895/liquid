<?php

interface MessageInterface
{
  public function mark(BaseNode $node);

  public function isMarked(BaseNode $node);

  public function receivers();

  public function isReceiver(MessengerInterface $processor);

  public function conditions();

  public function actions();
}
