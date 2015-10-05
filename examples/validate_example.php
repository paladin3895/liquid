<?php

require __DIR__ . '/../vendor/autoload.php';

use Liquid\Builders\ClosureBuilder;

$record = [
  'images' => [
    'img_1', 'img_2'
  ],
  'description' => 'this is description for come-stay',
  'factor' => 3,
];

$result = [
  'point' => 0
];

$policies = [
  'policy_image' => [
    'class' => 'function',
    'conditions' => [
      'images' => 'arr,length:1',
    ],
    'computations' => [
      'point' => 'point + 3 * factor'
    ],
  ],
  'policy_description' => [
    'class' => 'function',
    'conditions' => [
      'description' => 'string,regex:#come-stay#',
    ],
    'computations' => [
      'point' => 'point + 2 * factor'
    ],
  ],
];

$builder = new ClosureBuilder;

$functions = [];
foreach ($policies as $policy) {
  $functions[] = $builder->make($policy);
}

foreach ($functions as $function) {
  $result = $function($record, $result);
}

var_dump($result);
