<?php
use Liquid\Registry;
use Liquid\Nodes\Node;
use Liquid\Processors\ContinousProcessor;
use Liquid\Processors\ParallelProcessor;
use Liquid\Processors\WrappingProcessor;
use Liquid\Units\DummyDataProvider;
use Liquid\Units\InputLogger;
use Liquid\Units\RegexParser;
use Liquid\Units\ElementPicker;

$registry1 = new Registry();
$registry2 = new Registry();

$processor1 = new ContinousProcessor([new DummyDataProvider]);
$processor2 = new WrappingProcessor($registry2);
$processor3 = new ContinousProcessor([new RegexParser('#[a-zA-Z0-9]+$#')]);
$processor4 = new ParallelProcessor([new InputLogger]);

$processor5 = new ContinousProcessor([new DummyDataProvider]);
$processor6 = new ParallelProcessor([]);
$processor7 = new ContinousProcessor([new RegexParser('#[a-zA-Z0-9]+$#')]);
$processor8 = new ParallelProcessor([new InputLogger]);

#-------------------------------------------#
$node1 = new Node('prototype1', $registry1);
$node1->bind($processor1);

$node2 = new Node('prototype2', $registry1);
$node2->bind($processor2);

$node3 = new Node('prototype3', $registry1);
$node3->bind($processor3);

$node4 = new Node('prototype4', $registry1);
$node4->bind($processor4);

$node1->split([$node2, $node3]);
$node3->backward($node2);
$node4->hub([$node1, $node2, $node3]);
#-------------------------------------------#
$node5 = new Node('prototype5', $registry2);
$node5->bind($processor5);

$node6 = new Node('prototype6', $registry2);
$node6->bind($processor6);

$node7 = new Node('prototype7', $registry2);
$node7->bind($processor7);

$node8 = new Node('prototype8', $registry2);
$node8->bind($processor8);

$node5->split([$node6, $node7]);
$node7->backward($node6);
$node8->hub([$node5, $node6, $node7]);
#-------------------------------------------#
$node1->initialize();
$node5->initialize();
#-------------------------------------------#
$registry1->run();
