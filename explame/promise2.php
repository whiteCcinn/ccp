<?php
require_once 'include.php';

$promise = new Promise(function (ccp\Resolve $resolve, ccp\Reject $reject)
{
  $resolve('22');
});

$promise2 = $promise->then(null, function ($value)
{
  var_dump('promise2 reject' . $value);
});

$promise3 = $promise2->then(function ($value)
{
  var_dump('promise3 resolve' . $value);
});