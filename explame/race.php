<?php
require_once 'include.php';

ccp\Promise::race([
                 1, 2, 3, 4,
                 ccp\Promise::resolve(5),
                 ccp\Promise::resolve(6),
                 ccp\Promise::resolve(7),
             'eight',null,true,function(){echo 1;}]
)->then(function ($value)
{
  var_dump($value);
}, function ($value)
{
  var_dump($value->getMessage());
});