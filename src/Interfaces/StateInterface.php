<?php
namespace Liquid\Interfaces;

interface StateInterface
{
  public function compileProcess();
  public function compilePush();

  public function compileHandle();
  public function compileBroadcast();
}
