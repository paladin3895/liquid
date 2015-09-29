<?php

namespace Liquid\Units;

class DummyDataProvider extends BaseUnit
{
	public function process(array $record)
  {
    return [
      'name' => 'liquid',
      'description' => 'light weight framework for data analysis',
      'author' => 'David Pham',
      'version' => '0.1'
    ];
  }

	public static function getFormat()
	{
		return [
			'name' => 'string',
		];
	}
}
