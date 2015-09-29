<?php
namespace Liquid;

use Liquid\Builders\NodeBuilder;
use Liquid\Builders\RegistryBuilder;
use Liquid\Builders\ProcessorBuilder;
use Liquid\Builders\UnitBuilder;

use Liquid\Relation;
use Liquid\Entity;

class Schema
{
	const TYPE_NODE = 'node';
	const TYPE_REGISTRY = 'registry';
	const TYPE_PROCESSOR = 'processor';
	const TYPE_UNIT = 'unit';

	protected $objectPool = [];

	public function __construct()
	{
		$this->nodeBuilder = new NodeBuilder;
		$this->registryBuilder = new RegistryBuilder;
		$this->processorBuilder = new ProcessorBuilder;
		$this->unitBuilder = new UnitBuilder;
	}

	public function buildRelation(Relation $relation)
	{
		$relating = $relation->getRelating($this->objectPool);
		$related = $relation->getRelated($this->objectPool);
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
		if (isset($object))
			$this->objectPool[$entity->getType()][$entity->getId()] = $object;
	}
}
