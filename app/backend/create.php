<?php
require __DIR__ . '/../../vendor/autoload.php';

if (!isset($_POST['id'])) return;
$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
echo json_encode($diagram->get($_GET['id']));
