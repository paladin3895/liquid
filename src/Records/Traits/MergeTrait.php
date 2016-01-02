<?php
namespace Liquid\Records;

trait MergeTrait
{
  protected function _conditionedMerge(array $merging, array $merged)
  {
    foreach ($merged as $key => $value) {
      if (is_numeric($value)) {
        $merging[$key] = isset($merging[$key]) ? $merging[$key] + $value : $value;
      } elseif (is_string($value)) {
        $merging[$key] = $value;
      } elseif (is_bool($value)) {
        $merging[$key] = isset($merging[$key]) ? $merging[$key] || $value : $value;
      } elseif (is_array($value)) {
        $merging[$key] = isset($merging[$key]) ? array_merge((array)$merging[$key], $value) : $value;
      }
    }
    return $merging;
  }
}
