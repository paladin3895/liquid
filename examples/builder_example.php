<?php
require __DIR__ . '/../vendor/autoload.php';

use Liquid\Schema;
use Liquid\Models\Entity;
use Liquid\Models\Relation;

$entities = [
  ['id' => 1, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_1']],
  ['id' => 2, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_2']],
  ['id' => 3, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_3']],
  ['id' => 4, 'type' => 'registry', 'config' => ['name' => 'registry']],
];

$relations = [
  ['relating_id' => 1, 'related_id' => 4, 'action' => 'attach'],
  ['relating_id' => 2, 'related_id' => 4, 'action' => 'attach'],
  ['relating_id' => 3, 'related_id' => 4, 'action' => 'attach'],
  ['relating_id' => 1, 'related_id' => 2, 'action' => 'forward'],
  ['relating_id' => 1, 'related_id' => 3, 'action' => 'forward'],
  ['relating_id' => 2, 'related_id' => 3, 'action' => 'forward'],
];

$schema = new Schema;
foreach ($entities as $entity) {
  $schema->makeEntity(new Entity($entity));
}
foreach ($relations as $relation) {
  $schema->buildRelation(new Relation($relation));
}

$registries = $schema->getRegistry();
foreach ($registries as $registry) {
  $registry->initialize();
  $registry->run();
}
