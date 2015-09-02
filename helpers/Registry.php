<?php

class Registry implements HelperInterface
{
	protected $registry = [];
}

/*
[
	0 => [ $parser0 ],
	1 => [ $parser1, $parser2, $parser3 ],
	2 => [ $parser4, $parser5 ]

	input->$parser0->output-input->$parser1
	               ->output-input->$parser2
]	
 */