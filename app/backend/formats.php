<?php
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] != 'GET') return false;
$formats = Liquid\Builders\UnitBuilder::getFormats();

header('Content-Type: application/json');
echo json_encode($formats);
