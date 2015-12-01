<?php
namespace Liquid;

use Liquid\Builders\NodeBuilder;
use Liquid\Builders\RegistryBuilder;
use Liquid\Builders\ProcessorBuilder;
use Liquid\Builders\UnitBuilder;
use Liquid\Builders\ClosureBuilder;

use PDO;
use ReflectionClass;
use Liquid\Schema;

class Schema
{
	protected $objectPool = [];

	protected $objectGroup = [];

  protected $nodeBuilder;
  protected $registryBuilder;
  protected $processorBuilder;

	public function __construct(
		RegistryBuilder $registryBuilder,
		NodeBuilder $nodeBuilder,
		ProcessorBuilder $processorBuilder
	) {
		$this->nodeBuilder = $nodeBuilder;
		$this->registryBuilder = $registryBuilder;
		$this->processorBuilder = $processorBuilder;
	}

  public function build(array $config)
  {
		// $config = $schema->getConfig();
		$this->objectPool['registry'] = $this->registryBuilder->make($config);

		$nodes_config = json_decode($config['nodes'], true);
		if (!empty($nodes_config)) $this->_buildNodes($nodes_config);

		$links_config = json_decode($config['links']);
		if (!empty($links_config)) $this->_buildLinks($links_config);

		$this->_initialize();
		return $this->objectPool['registry'];
  }

	protected function _buildNodes(array $nodes_config)
	{
		foreach ($nodes_config as $node_config) {
			$node = $this->nodeBuilder->make($node_config);
			$processor = $this->processorBuilder->make($node_config);
			$node->bind($processor);
			$this->objectPool['nodes'][$node_config['key']] = $node;
		}
	}

	protected function _buildLinks(array $links_config)
	{
		foreach ($links_config as $link) {
			$from = $this->objectPool['nodes'][$link->from];
			$to = $this->objectPool['nodes'][$link->to];
			$from->forward($to);
		}
	}

	protected function _initialize()
	{
		if (empty($this->objectPool['nodes'])) return;
		foreach ($this->objectPool['nodes'] as $node) {
			$node->register($this->objectPool['registry']);
		}
	}
}
