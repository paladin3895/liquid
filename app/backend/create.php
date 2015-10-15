<?php
require __DIR__ . '/../../vendor/autoload.php';
use Liquid\Helpers\JsonAdapter;

if ($_SERVER['REQUEST_METHOD'] != 'POST') return false;
$params = $_POST;
$format = ['name', 'description', 'nodes', 'links'];

foreach ($format as $key) {
  if (!isset($params[$key])) return false;
}

$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
echo $diagram->create(JsonAdapter::toBackend($params));
