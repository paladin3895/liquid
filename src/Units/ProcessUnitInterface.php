<?php

namespace Liquid\Units;

use Liquid\Processors\BaseProcessor;

interface ProcessUnitInterface
{
	/**
	 * @return array
	 */
	public static function getFormat();

	/**
	 * @return closure 
	 */
	public static function compile();
}
