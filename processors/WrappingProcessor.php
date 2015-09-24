<?php
require_once('Processor.php');

class WrappingProcessor extends Processor
{
  protected $registry;

  public function __construct(Registry $registry)
  {
    $this->registry = $registry;
  }

  public function process(array $data)
  {
    $this->registry->setInput($data);
    $this->registry->run();
    return $this->registry->getOutput();
  }
}
