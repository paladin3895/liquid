<?php
require __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') return false;

$params = $_POST;

$diagram = new Liquid\Models\Diagram;
$schema = new Liquid\Schema;
$registry = $schema->build($diagram->get($params['id']));
// var_dump(Liquid\Builders\UnitBuilder::getFormats()); exit;
$result = $registry->process(['test' => json_decode($params['test'], true)]);

header('Content-Type: application/json');
echo json_encode($result);
