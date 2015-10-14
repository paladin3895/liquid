<?php
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] != 'PUT') return false;
parse_str(file_get_contents('php://input'), $params);
$format = ['id', 'name', 'description', 'nodes', 'links'];
foreach ($format as $key) {
  if (!isset($params[$key])) return false;
}
$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
echo json_encode($diagram->update($params['id'], $params));
