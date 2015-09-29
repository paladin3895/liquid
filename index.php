<?php
require __DIR__ . '/vendor/autoload.php';

$schema = new Liquid\Schema;

foreach ($entities as $entity) {
  $schema->makeEntity($entity);
}

foreach ($relations as $relation) {
  $schema->buildRelation($relation);
}
