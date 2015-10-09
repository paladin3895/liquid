<?php
namespace Liquid;

use Liquid\Builders\NodeBuilder;
use Liquid\Builders\RegistryBuilder;
use Liquid\Builders\ProcessorBuilder;
use Liquid\Builders\UnitBuilder;
use Liquid\Builders\ClosureBuilder;

use Liquid\Models\Relation;
use Liquid\Models\Entity;

class AdvancedSchema
{
	const TYPE_NODE = 'node';
	const TYPE_REGISTRY = 'registry';
	const TYPE_PROCESSOR = 'processor';
	const TYPE_UNIT = 'unit';

	protected $objectPool = [];

	protected $objectGroup = [];

	public function __construct()
	{
		$this->nodeBuilder = new NodeBuilder;
		$this->registryBuilder = new RegistryBuilder;
		$this->processorBuilder = new ProcessorBuilder;
		$this->unitBuilder = new UnitBuilder;
	}

	public function buildRelation(Relation $relation)
	{
		$relating_id = $relation->getRelating();
		$relating = isset($this->objectPool[$relating_id])
			? $this->objectPool[$relating_id] : false;

		$related_id = $relation->getRelated();
		$related = isset($this->objectPool[$related_id])
			? $this->objectPool[$related_id] : false;

		if (!$relating || !$related) return false;
		$relating->forward($related);
	}

  public function makeEntity(Entity $entity)
	{

	}

	public function saveEntity(array $entity)
	{

	}

	public function saveRelation(array $relation)
	{

	}

	public function getRegistry()
	{
		return $this->objectGroup['registry'];
	}
}
