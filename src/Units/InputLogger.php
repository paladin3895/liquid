<?php

namespace Liquid\Units;

class InputLogger extends BaseUnit implements FormatInterface
{
  public function process(array $record)
  {
    $output = $record;
    echo '======== Record ========<br/>';
    echo 'label: ' . $this->recordLabel . '<br/>';
    echo '-----------------------------<br/>';
    foreach ($record as $key => $value) {
      echo $key . ' : ' . $value . '<br/>';
      echo '..............................<br/>';
    }
    return $output;
  }

  public static function getFormat()
  {
    return [

    ];
  }
}
