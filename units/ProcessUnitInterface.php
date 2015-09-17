<?php
require_once('OutputLogger.php');
require_once('RegexParser.php');

interface ProcessUnitInterface
{
	public function process(array $input);
}
