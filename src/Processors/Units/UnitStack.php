<?php
namespace Liquid\Processors\Units;

use Liquid\Processors\ProcessUnitInterface;
use SplObjectStorage;

class UnitStack extends SplObjectStorage
{
  public function attach(ProcessUnitInterface $obj, $inf = NULL)
  {
    parent::attach($obj, $inf);
  }

  public function addAll(UnitStack $storage)
  {
    parent::addAll($storage);
  }
}
