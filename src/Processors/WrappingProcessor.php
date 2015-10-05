<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Registry;

class WrappingProcessor extends BaseProcessor
{
  protected $registry;

  public function wrap(Registry $registry)
  {
    $this->registry = $registry;
  }

  public function process(array $data, array $result_input)
  {
    $this->registry->initialize();
    $this->registry->setInput($data);
    $this->registry->setResult($result_input);
    $this->registry->run();

    $this->setOutput($this->registry->getResult());
  }
}
