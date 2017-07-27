<?php
require_once 'include.php';

ccp\Promise::all([
                 ccp\Promise::reject(new Exception('exception 1')),
                 ccp\Promise::resolve(6),
                 ccp\Promise::reject(new Exception('exception 2'))]
)->then(function ($value)
{
  var_dump($value);
}, function ($value)
{
  var_dump($value->getMessage());
});