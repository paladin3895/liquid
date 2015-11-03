<?php
namespace Liquid\Processors\Units;

use Liquid\Processors\BaseProcessor;

interface ProcessUnitInterface
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
	 * @param array $config
	 * @return closure
	 */
	public static function compile(array $config);
}
