<?php
require __DIR__ . '/../vendor/autoload.php';

use Liquid\Schema;
use Liquid\Models\Entity;
use Liquid\Models\Relation;

$entities = [
  ['id' => 1, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_1']],
  ['id' => 2, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_2']],
  ['id' => 3, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_3']],
  ['id' => 4, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_4']],

  ['id' => 5, 'type' => 'registry', 'config' => ['name' => 'registry']],

  ['id' => 6, 'type' => 'processor', 'config' => [
      'class' => 'ContinousProcessor',
      'name' => 'processor_1',
      'units' => [
        ['class' => 'DummyDataProvider', 'name' => 'unit_1'],
      ]
    ]
  ],
  ['id' => 7, 'type' => 'processor', 'config' => [
      'class' => 'ExecutiveProcessor',
      'name' => 'processor_2',
      'units' => [
        ['class' => 'ElementPicker', 'name' => 'unit_4', 'elements' => ['name', 'description', 'version']],
        ['class' => 'CommandTrigger', 'name' => 'unit_3', 'conditions' => ['name' => 'liquid'], 'receivers' => ['node_3', 'node_4'], 'actions' => ['display' => '', 'terminate' => '']]
      ]
    ]
  ],
  ['id' => 8, 'type' => 'processor', 'config' => [
      'class' => 'ExecutiveProcessor',
      'name' => 'processor_3',
      'units' => [
        ['class' => 'RegexParser', 'name' => 'unit_2', 'key' => 'name', 'signature' => '#[a-zA-Z0-9]+$#'],
      ]
    ]
  ],
  ['id' => 9, 'type' => 'processor', 'config' => [
      'class' => 'ParallelProcessor',
      'name' => 'processor_4',
      'units' => [
        ['class' => 'InputLogger', 'name' => 'unit_5']
      ]
    ]
  ],
];

$relations = [
  ['relating_id' => 1, 'related_id' => 5, 'action' => 'attach'],
  ['relating_id' => 2, 'related_id' => 5, 'action' => 'attach'],
  ['relating_id' => 3, 'related_id' => 5, 'action' => 'attach'],
  ['relating_id' => 4, 'related_id' => 5, 'action' => 'attach'],

  ['relating_id' => 1, 'related_id' => 6, 'action' => 'bind'],
  ['relating_id' => 2, 'related_id' => 7, 'action' => 'bind'],
  ['relating_id' => 3, 'related_id' => 8, 'action' => 'bind'],
  ['relating_id' => 4, 'related_id' => 9, 'action' => 'bind'],

  ['relating_id' => 1, 'related_id' => 2, 'action' => 'forward'],
  ['relating_id' => 2, 'related_id' => 3, 'action' => 'forward'],
  ['relating_id' => 1, 'related_id' => 4, 'action' => 'forward'],
  ['relating_id' => 2, 'related_id' => 4, 'action' => 'forward'],
  ['relating_id' => 3, 'related_id' => 4, 'action' => 'forward'],
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
