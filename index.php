<?php
require __DIR__ . '/vendor/autoload.php';

$diagram = new Liquid\Models\Diagram;
$schema = new Liquid\Schema;

$registry = $schema->build($diagram->get(5));
var_dump($registry->process(['test' => ['title' => 'liquid with come-stay']]));
