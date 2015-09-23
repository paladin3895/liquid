<?php
require_once('../nodes/Node.php');
require_once('../processors/ContinousProcessor.php');
require_once('../processors/ParallelProcessor.php');
require_once('../helpers/Registry.php');
require_once('../units/InputLogger.php');
require_once('../units/DummyDataProvider.php');
require_once('../units/RegexParser.php');

$registry = new Registry();
$processor1 = new ContinousProcessor([new DummyDataProvider]);
$processor2 = new ContinousProcessor([]);
$processor3 = new ContinousProcessor([new RegexParser('#[a-zA-Z0-9]+$#')]);
$processor4 = new ParallelProcessor([new InputLogger]);


$node1 = new Node('prototype1', $registry, $processor1);
$node2 = new Node('prototype2', $registry, $processor2);
$node3 = new Node('prototype3', $registry, $processor3);
$node4 = new Node('prototype4', $registry, $processor4);

$node1->split([$node2, $node3]);
$node3->backward($node2);
$node4->hub([$node1, $node2, $node3]);

$node1->initialize();
$registry->run();
