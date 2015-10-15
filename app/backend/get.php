<?php
require __DIR__ . '/../../vendor/autoload.php';
use Liquid\Helpers\JsonAdapter;

if ($_SERVER['REQUEST_METHOD'] != 'GET') return false;
$params = $_GET;
if (!isset($params['id'])) return;
$diagram = new Liquid\Models\Diagram;
header('Content-Type: application/json');
$schema = $diagram->get($params['id']);
echo JsonAdapter::toFrontend($diagram->get($params['id']));
