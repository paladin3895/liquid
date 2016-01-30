<?php
namespace Liquid\Processors\Units;

use Liquid\Interfaces\ProcessUnitInterface;
use SplObjectStorage;

class UnitStack extends SplObjectStorage
{
  public function attach($obj, $inf = NULL)
  {
    if (!($obj instanceof ProcessUnitInterface))
      throw new \Exception('invalid object attach to UnitStack');
    parent::attach($obj, $inf);
  }

  public function addAll($storage)
  {
    if (!($storage instanceof UnitStack))
      throw new \Exception('invalid object attach to UnitStack');
    parent::addAll($storage);
  }
}
