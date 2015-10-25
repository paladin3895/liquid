<?php
namespace Liquid\Processors;

use Liquid\Processors\BaseProcessor;
use Liquid\Registry;
use Liquid\Records\Collection;
use Liquid\Records\Record;

class WrappingProcessor extends BaseProcessor
{
  protected $registry;

  public function wrap(Registry $registry)
  {
    $this->registry = $registry;
  }

  public function process(Collection $collection)
	{
		return $record;
	}
}
