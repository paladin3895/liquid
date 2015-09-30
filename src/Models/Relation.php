<?php

namespace Liquid\Models;

class Relation
{
  protected $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function getRelating()
  {
    return $this->data['relating_id'];
  }

  public function getRelated()
  {
    return $this->data['related_id'];
  }

  public function getAction()
  {
    return $this->data['action'];
  }
}
