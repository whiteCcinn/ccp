<?php
require_once 'include.php';

function a()
{
  return '2';
}

\ccp\Promise::co(function ()
{
  $a = yield a();
  var_dump($a);
  $b = yield 2;
  var_dump($b);
  $c = yield 3;
  var_dump($c);
  $d = yield 4;
  var_dump($d);
  $e = yield 5;
  var_dump($e);

  return 'down';
})->then(function ($value)
{
  var_dump($value);
});