<?php
require_once 'include.php';

ccp\Promise::all([
                     ccp\Promise::resolve(5),
                     ccp\Promise::resolve(6),
                     ccp\Promise::resolve(7)]
)->then(function ($value)
{
  var_dump($value);
});