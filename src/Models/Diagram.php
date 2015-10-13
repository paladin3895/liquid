<?php

namespace Liquid\Models;
use PDO;
use ReflectionClass;

class Diagram
{
  protected $format = [
    'id' => 'int',
    'name' => 'string',
    'description' => 'string',
    'nodes' => 'array',
    'links' => 'array',
  ];

  protected $table = 'diagram';

  protected $connectionParams = [
		'sqlite:/home/knight/Documents/Project/liquid/database/liquid.db', null, null,
		// [PDO::ATTR_PERSISTENT => true]
  ];

  protected $connection;

  public function __construct()
  {
    $pdo = new ReflectionClass(PDO::class);
    $this->connection = $pdo->newInstanceArgs($this->connectionParams);
    // $this->_migration();
  }

  public function migration()
  {
    $this->connection->exec("CREATE TABLE {$this->table} (
      id INTEGER AUTOINCREMENT,
      name VARCHAR(100) NOT NULL,
      description TEXT,
      nodes TEXT,
      links TEXT,
      PRIMARY KEY(id)
    )");
  }

  public function index()
  {
    $sql = "SELECT id, name, description FROM {$this->table}";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function get($id)
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  public function create(array $schema)
  {
    $sql = "INSERT INTO {$this->table} (name, description, nodes, links) VALUES (:name, :description, :nodes, :links)";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue('name', isset($schema['name']) ? $schema['name'] : '', PDO::PARAM_STR);
    $stmt->bindValue('description', isset($schema['description']) ? $schema['description'] : '', PDO::PARAM_STR);
    $stmt->bindValue('nodes', isset($schema['nodes']) ? $schema['nodes'] : '', PDO::PARAM_STR);
    $stmt->bindValue('links', isset($schema['links']) ? $schema['links'] : '', PDO::PARAM_STR);
    if ($stmt->execute()) return $this->connection->lastInsertId();
  }

  public function update($id, array $schema)
  {
    $sql = "UPDATE {$this->table} SET name = :name, description = :description, nodes = :nodes, links = :links WHERE id = :id";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->bindValue('name', isset($schema['name']) ? $schema['name'] : '', PDO::PARAM_STR);
    $stmt->bindValue('description', isset($schema['description']) ? $schema['description'] : '', PDO::PARAM_STR);
    $stmt->bindValue('nodes', isset($schema['nodes']) ? $schema['nodes'] : '', PDO::PARAM_STR);
    $stmt->bindValue('links', isset($schema['links']) ? $schema['links'] : '', PDO::PARAM_STR);
    return $stmt->execute();
  }

  public function delete($id)
  {
    $sql = "DELETE FROM {$this->table} WHERE id = :id";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }
}
