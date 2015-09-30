<?php
require __DIR__ . '/../vendor/autoload.php';

use Liquid\Nodes\Node;
use Liquid\Processors\ContinousProcessor;
use Liquid\Processors\ParallelProcessor;
use Liquid\Registry;
use Liquid\Units\InputLogger;
use Liquid\Units\ElementPicker;
use Liquid\Units\DummyDataProvider;
use Liquid\Units\RegexParser;

$registry = new Registry();

$processor1 = new ContinousProcessor();
$processor1->stack(new DummyDataProvider);

$processor2 = new ContinousProcessor();
$processor2->stack(new ElementPicker(['name', 'version']));

$processor3 = new ContinousProcessor();
$processor3->stack(new RegexParser('author', '#[a-zA-Z0-9]+$#'));

$processor4 = new ParallelProcessor();
$processor4->stack(new InputLogger);

$node1 = new Node();
$node1->bind($processor1);

$node2 = new Node();
$node2->bind($processor2);

$node3 = new Node();
$node3->bind($processor3);

$node4 = new Node();
$node4->bind($processor4);

$node1->split([$node2, $node3]);
$node3->backward($node2);
$node4->hub([$node1, $node2, $node3]);

$node1->initialize($registry);
$registry->run();
