<?php
require_once('nodes/Node.php');
require_once('processors/Processor.php');
require_once('helpers/Registry.php');
require_once('units/ProcessUnitInterface.php');

$registry = new Registry();
$processor = new ContinousProcessor([new OutputLogger()]);

$node1 = new Node('prototype1', $registry, $processor);
$node2 = new Node('prototype2', $registry, $processor);
$node3 = new Node('prototype3', $registry, $processor);
$node1->split([$node2, $node3]);
$node3->backward($node2);
$node1->initialize();
$registry->run();
