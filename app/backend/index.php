<?php
require __DIR__ . '/../../vendor/autoload.php';

$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
echo json_encode($diagram->index());
