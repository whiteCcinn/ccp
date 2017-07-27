<?php
require_once 'include.php';

(new ccp\Promise(function (ccp\Resolve $resolve, ccp\Reject $reject)
{
  $resolve('test');
}))->then(function ($value)
{
  var_dump('1' . $value);
})->then(function ($value)
{
  throw new Exception('sad');
})->catch(function ($value)
{
  var_dump($value->getMessage());
});