<?php
require __DIR__ . '/vendor/autoload.php';

$diagram = new Liquid\Models\Diagram;
$schema = new Liquid\Schema;

$registry = $schema->build($diagram->get(1));

var_dump($registry->process(['test' => ['name' => 'liquid']]));
