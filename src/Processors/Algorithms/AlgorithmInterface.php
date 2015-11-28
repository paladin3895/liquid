<?php
namespace Liquid\Processors\Algorithms;

use Liquid\Processors\Units\UnitStack;

interface AlgorithmInterface
{
	/**
	 * @return array
	 */
	public static function getFormat();

	/**
	 * @param array $config
	 * @return boolean
	 */
	public static function validate(array $config);

	/**
	 * @return closure
	 */
	public function compile(UnitStack $units);
}
