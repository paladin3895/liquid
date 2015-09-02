<?php

interface ParserInterface
{
	public function parse(array $input);
}

class BaseNode
{
	protected $previous = null;
	protected $nexts = [];

	public $registry = null;
	protected $logger = null;

	protected $parser = null;

	protected $depth = 0;

	protected $input = [];
	protected $output = [];


	public function __construct(ParserInterface $parser, Registry $registry, Logger $logger)
	{
		$this->parser = $parser;
		$this->registry = $registry;
		$this->logger = $logger;
	}
	// create a piping stucture in which
	// output of one node is input of the nexts
	public function pipe(BaseNode $next)
	{
		$this->nexts[] = $next;
		$next->previous = $this;
		$next->depth = $this->depth + 1;
	}

	public function getData(array $input)
	{
		$this->input = $input;
	}

	public function parse()
	{
		$this->output = $this->parser->parse($this->input);
		$this->logger->log($this->output);

		foreach ($this->nexts as $node) {
			$node->input = $this->output;
		}
	}

	public function getDepth()
	{
		return $this->depth;
	}

	public function register()
	{
		$this->registry->register($this);
		foreach ($this->nexts as $node) {
			$node->register();
		}
	}

}

class Registry
{
	public $registries = [];

	public function register(BaseNode $node)
	{
		$this->registries[$node->getDepth()][] = $node;
	}

	public function display()
	{
		foreach ($this->registries as $key => $value) {
			echo 'depth ' . $key . ' has ' . count($value) . ' node:<br>';
		}
	}

	public function run()
	{
		foreach ($this->registries as $depth) {
			foreach ($depth as $parser) {
				$parser->parse();
			}
		}
	}
}

class Logger
{
	public function log($output) {
		print_r($output);
	}
}

class Parser implements ParserInterface
{
	public function parse(array $input)
	{
		$input[] = rand();
		return $input;
	}
}

class UrlCrawler
{
	
}