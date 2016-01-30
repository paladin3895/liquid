<?php
namespace Liquid\Interfaces;

use Liquid\Processors\Units\UnitStack;
use Liquid\Interfaces\ConfigurableInterface;

interface AlgorithmInterface extends ConfigurableInterface
{
	/**
	 * @return closure
	 */
	public function compile(UnitStack $units);
}
