<?php
namespace Liquid\Nodes\States;

interface StateInterface
{
  public function compileProcess();
  public function compilePush();

  public function compileHandle();
  public function compileBroadcast();
}
