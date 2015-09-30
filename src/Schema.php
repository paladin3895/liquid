<?php
namespace Liquid;

use Liquid\Builders\NodeBuilder;
use Liquid\Builders\RegistryBuilder;
use Liquid\Builders\ProcessorBuilder;
use Liquid\Builders\UnitBuilder;

use Liquid\Models\Relation;
use Liquid\Models\Entity;

class Schema
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
		call_user_func([$relating, $relation->getAction()], $related);
	}

  public function makeEntity(Entity $entity)
	{
		switch ($entity->getType()) {
			case self::TYPE_NODE:
				$object = $this->nodeBuilder->make($entity->getConfig());
				break;
			case self::TYPE_REGISTRY:
				$object = $this->registryBuilder->make($entity->getConfig());
				break;
			case self::TYPE_PROCESSOR:
				$object = $this->processorBuilder->make($entity->getConfig());
				break;
			case self::TYPE_UNIT:
				$object = $this->unitBuilder->make($entity->getConfig());
				break;
		}
		if (!isset($object)) return false;
		$this->objectGroup[$entity->getType()][$entity->getId()] = $object;
		$this->objectPool[$entity->getId()] = $object;
	}

	public function getRegistry()
	{
		return $this->objectGroup['registry'];
	}
}
