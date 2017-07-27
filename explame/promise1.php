<?php
require_once 'include.php';

$promise = new Promise(function (ccp\Resolve $resolve, ccp\Reject $reject)
{
  $resolve(5);
});

$promise->then(function ($value)
{
  echo 'first' . PHP_EOL;
  var_dump($value);
  throw new Error('test');
}, function ($value)
{
  var_dump(2);
})->then(function ($value)
{
  echo 'secondfull' . PHP_EOL;
  var_dump($value);
}, function ($value)
{
  echo 'secondreject' . PHP_EOL;
  var_dump($value);
});