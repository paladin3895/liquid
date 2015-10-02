<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Registry;

class WrappingProcessor extends BaseProcessor
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
