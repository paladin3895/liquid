<?php
require 'parsers/ParserInterface.php';

$parser = new Parser();
$registry = new Registry();
$logger = new Logger();

$crawler1 = new BaseNode(
	$parser,
	$registry,
	$logger
);

$crawler2 = new BaseNode(
	$parser,
	$registry,
	$logger
);

$crawler3 = new BaseNode(
	$parser,
	$registry,
	$logger
);

$crawler4 = new BaseNode(
	$parser,
	$registry,
	$logger
);

$crawler1->pipe($crawler2);
$crawler1->pipe($crawler3);

$crawler2->pipe($crawler4);

$crawler1->register();

$crawler1->getData(['123']);

$crawler1->registry->run();
