<?php
namespace Liquid;

use Liquid\Registry;
use Liquid\Nodes\BaseNode;
use Liquid\Builders\BuilderInterface;

use PDO;
use ReflectionClass;
use Liquid\Schema;

class Schema
{
  protected $node;
  protected $registry;
  protected $builder;

	public function __construct(array $config = []) {
		$reg_ref = new ReflectionClass($config['registry']);
		if ($reg_ref->isInstantiable()
      && ($reg_ref->isSubclassOf('Liquid\Registry') || $config['registry'] == 'Liquid\Registry')
    ) {
			$this->registry = $reg_ref;
		} else {
			throw new \Exception('invalid registry class in config');
		}

		$node_ref = new ReflectionClass($config['node']);
		if ($node_ref->isInstantiable() && $node_ref->isSubclassOf('Liquid\Nodes\BaseNode')) {
			$this->node = $node_ref;
		} else {
			throw new \Exception('invalid node class in config');
		}

		$builder_ref = new ReflectionClass($config['builder']);
		if ($builder_ref->isInstantiable()) {
			$this->builder = $builder_ref->newInstance();
		}
	}

  public function build(array $config_nodes, array $config_links)
  {
		$registry = $this->registry->newInstance();

		$nodes = $this->_buildNodes($config_nodes, $registry);
		$this->_buildLinks($config_links, $nodes);

		$registry->initialize();
		return $registry;
  }

	protected function _buildNodes(array $config_nodes, Registry $registry)
	{
		$nodes = [];
		foreach ($config_nodes as $node) {
				$policy = $this->builder->make($node);
				$nodes[$node['id']] = $this->node->newInstance($node['id']);
				$nodes[$node['id']]->bind($policy);
				$nodes[$node['id']]->register($registry);
		}
		return $nodes;
	}

	protected function _buildLinks(array $config_links, array $nodes)
	{
		foreach ($config_links as $link) {
				if (isset($nodes[$link['node_from']]) && isset($nodes[$link['node_to']])) {
						$nodes[$link['node_from']]->forward($nodes[$link['node_to']]);
				}
		}
	}
}
