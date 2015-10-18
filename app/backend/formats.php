<?php
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] != 'GET') return false;
$formats = Liquid\Builders\UnitBuilder::getFormats();

foreach ($formats as $class => &$format) {
  foreach ($format as $key => &$value) {
    switch ($value) {
      case 'array': $value = []; break;
      case 'string': $value = 'string'; break;
      case 'integer': $value = 0; break;
      default: $value = null; break;
    }
  }
  $format['class'] = $class;
}

header('Content-Type: application/json');
echo json_encode($formats);
