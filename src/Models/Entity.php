<?php

namespace Liquid\Models;

class Entity
{
  // protected $db;
  //
  // protected $table;
  //
  // protected $primaryKey;
  //
  // protected $format = [
  //   'id' => 'int',
  //   'type' => 'string',
  //   'configuration' => 'string',
  // ];
  //
  // protected $record;
  //
  // public function __construct(PDO $db, $table)
  // {
  //   $this->db = $db;
  //   $this->table = (string)$table;
  //   $this->primaryKey = $this->db
  //     ->query("SHOW KEYS FROM {$this->table}" .
  //     " WHERE Key_name = 'PRIMARY'")->fetch();
  // }
  //
  // public function find($id)
  // {
  //   return $this->db()
  // }
  //
  // public function get()
  // {
  //
  // }
  //
  // public function create(array $record)
  // {
  //
  // }
  //
  // public function update($id, array $record)
  // {
  //
  // }
  //
  // public function delete($id)
  // {
  //
  // }

  protected $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function getId()
  {
    return $this->data['id'];
  }

  public function getConfig()
  {
    return $this->data['config'];
  }

  public function getType()
  {
    return $this->data['type'];
  }
}
