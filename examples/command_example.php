<?php
require __DIR__ . '/../vendor/autoload.php';

use Liquid\Registry;
use Liquid\Messages\Command;
use Liquid\Nodes\Node;
use Liquid\Processors\ContinousProcessor;
use Liquid\Processors\ParallelProcessor;
use Liquid\Processors\ExecutiveProcessor;
use Liquid\Units\CommandTrigger;
use Liquid\Units\DummyDataProvider;
use Liquid\Units\InputLogger;
use Liquid\Units\RegexParser;

$registry = new Registry;

$processor1 = new ContinousProcessor;
$processor2 = new ExecutiveProcessor;
$processor3 = new ExecutiveProcessor;
$processor4 = new ParallelProcessor;

$node1 = new Node;
$node2 = new Node;
$node3 = new Node;
$node4 = new Node;

$processor1->stack(new DummyDataProvider);
$processor2->stack(new CommandTrigger(['name' => 'liquid'],
  [$node3->getName(), $node4->getName()],
  ['node' => 'terminate']));
$processor3->stack(new RegexParser('name', '#[a-zA-Z0-9]+$#'));
$processor4->stack(new InputLogger);

$node1->bind($processor1);
$node2->bind($processor2);
$node3->bind($processor3);
$node4->bind($processor4);

$node1->split([$node2, $node3]);
$node3->backward($node2);
$node4->hub([$node1, $node2, $node3]);

$node1->initialize($registry);
$registry->run();
