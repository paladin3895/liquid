<?php
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] != 'GET') return false;
$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
echo json_encode($diagram->index());
