<?php
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') return false;
parse_str(file_get_contents('php://input'), $params);
if (!isset($params['id'])) return;
$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
echo json_encode($diagram->delete($params['id']));
