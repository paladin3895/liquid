<?php
namespace Liquid\Helpers;

class JsonAdapter
{
  public static $aliases = [
    'ContinousProcessor' => 'continous',
    'ParallelProcessor'  => 'parallel',
    'ExecutiveProcessor' => 'executive',
    'MergingProcessor'   => 'merging'
  ];

  public static $nodeFormat = [
    'key', 'name', 'category', 'units'
  ];

  public static $linkFormat = [
    'from', 'to'
  ];

  public static function toFrontend(array $diagram)
  {
    $nodes = json_decode($diagram['nodes'], TRUE);
    foreach ($nodes as &$node) {
      if (!array_key_exists($node['class'], self::$aliases)) continue;
      $node['category'] = self::$aliases[$node['class']];
      unset($node['class']);
    }
    $diagram['nodes'] = json_encode($nodes);
    return json_encode($diagram);
  }

  public static function toBackend(array $diagram)
  {
    $nodes = json_decode($diagram['nodes'], true);
    $output_nodes = [];
    foreach ($nodes as $node) {
      $node = self::_checkFormat($node, self::$nodeFormat);
      if (!$node) throw new \Exception('invalid node format');

      if (!in_array($node['category'], self::$aliases))
        throw new \Exception('invalid node category');

      $node['class'] = array_search($node['category'], self::$aliases);
      unset($node['category']);
      $output_nodes[] = $node;
    }
    $diagram['nodes'] = json_encode($output_nodes);

    $links = json_decode($diagram['links'], true);
    $output_links = [];
    foreach ($links as $link) {
      $link = self::_checkFormat($link, self::$linkFormat);
      if (!$link) throw new \Exception('invalid link format');
      $output_links[] = $link;
    }
    $diagram['links'] = json_encode($output_links);
    return $diagram;
  }

  protected static function _checkFormat(array $data, array $format)
  {
    $output = [];
    foreach ($format as $key) {
      if (!isset($data[$key])) return false;
      $output[$key] = $data[$key];
    }
    return $output;
  }
}
