<?php

class OutputLogger implements ProcessUnitInterface
{
  public function process(array $input)
  {
    $output = $input;
    foreach ($input as $key => $value) {
      echo '    ' . $key . ' : ' . $value . '<br/>';
    }
    return $output;
  }
}
