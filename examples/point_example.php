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
  ['id' => 5, 'type' => 'node', 'config' => ['class' => 'Node', 'name' => 'node_5']],

  ['id' => 6, 'type' => 'registry', 'config' => ['name' => 'registry_1']],
  ['id' => 12, 'type' => 'registry', 'config' => ['name' => 'registry_2']],

  ['id' => 7, 'type' => 'processor', 'config' => [
      'class' => 'ContinousProcessor',
      'name' => 'processor_1',
      'units' => [
        ['class' => 'DummyDataProvider',
          'name' => 'unit_1',
          'data' => [
            'title' => 'best property of Saigon',
            'description' => 'this is a best property provided by come-stay',
            'images' => ['img_1', 'img_2', 'img_3'],
            'level' => 3
          ]
        ],
        ['class' => 'InputLogger', 'name' => 'unit_2']
      ]
    ]
  ],
  ['id' => 8, 'type' => 'processor', 'config' => [
      'class' => 'WrappingProcessor',
      'name' => 'processor_2',
      'units' => [],
    ]
  ],
  ['id' => 9, 'type' => 'processor', 'config' => [
      'class' => 'ContinousProcessor',
      'name' => 'processor_3',
      'units' => [
        ['class' => 'ElementPicker', 'elements' => ['title', 'description', 'images', 'level'], 'name' => 'unit_3'],
        ['class' => 'function',
          'conditions' => [
            'title' => 'string,length:10',
          ],
          'computations' => [
            'point' => 'point + level * 2',
          ],
        ],
        ['class' => 'function',
          'conditions' => [
            'description' => 'string,regex:#come-stay#',
          ],
          'computations' => [
            'point' => 'point + level * 3',
          ],
        ]
      ]
    ]
  ],
  ['id' => 10, 'type' => 'processor', 'config' => [
      'class' => 'ContinousProcessor',
      'name' => 'processor_4',
      'units' => [
        ['class' => 'ElementPicker', 'elements' => ['images', 'level'], 'name' => 'unit_4'],
        ['class' => 'function',
          'conditions' => [
            'images' => 'arr,length:3',
          ],
          'computations' => [
            'point' => 'point + level * 2',
          ],
        ],
        ['class' => 'function',
          'conditions' => [
            'images' => 'arr,length:4',
          ],
          'computations' => [
            'point' => 'point + level * 5',
          ],
        ],
      ]
    ]
  ],
  ['id' => 11, 'type' => 'processor', 'config' => [
      'class' => 'ParallelProcessor',
      'name' => 'processor_5',
      'units' => [
        ['class' => 'InputLogger', 'name' => 'unit_5']
      ]
    ]
  ],
];

$relations = [
  ['relating_id' => 1, 'related_id' => 6, 'action' => 'attach'],
  ['relating_id' => 2, 'related_id' => 6, 'action' => 'attach'],
  ['relating_id' => 3, 'related_id' => 12, 'action' => 'attach'],
  ['relating_id' => 4, 'related_id' => 12, 'action' => 'attach'],
  ['relating_id' => 5, 'related_id' => 6, 'action' => 'attach'],

  ['relating_id' => 1, 'related_id' => 7, 'action' => 'bind'],
  ['relating_id' => 2, 'related_id' => 8, 'action' => 'bind'],
  ['relating_id' => 3, 'related_id' => 9, 'action' => 'bind'],
  ['relating_id' => 4, 'related_id' => 10, 'action' => 'bind'],
  ['relating_id' => 5, 'related_id' => 11, 'action' => 'bind'],

  ['relating_id' => 8, 'related_id' => 12, 'action' => 'wrap'],

  ['relating_id' => 1, 'related_id' => 2, 'action' => 'forward'],
  ['relating_id' => 2, 'related_id' => 5, 'action' => 'forward'],

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
$registries[6]->initialize();
$registries[6]->run();
