<?php
require_once('nodes/Node.php');
require_once('processors/Processor.php');
require_once('helpers/Registry.php');
require_once('units/InputLogger.php');
require_once('units/DummyDataProvider.php');
require_once('units/RegexParser.php');

$registry = new Registry();
$processor1 = new ContinousProcessor([new InputLogger]);
$processor2 = new ContinousProcessor([new InputLogger, new DummyDataProvider]);
$processor3 = new ContinousProcessor([new RegexParser('#[a-zA-Z0-9]+$#'), new InputLogger]);

$node1 = new Node('prototype1', $registry, $processor2);
$node2 = new Node('prototype2', $registry, $processor1);
$node3 = new Node('prototype3', $registry, $processor3);
$node1->split([$node2, $node3]);
$node3->backward($node2);
$node1->initialize();
$registry->run();
