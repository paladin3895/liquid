<?php
namespace Liquid\Processors;

use Liquid\Processors\Processor;
use Liquid\Registry;

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
