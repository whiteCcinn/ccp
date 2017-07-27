<?php
require_once 'include.php';

ccp\Promise::resolve(5)->then(function ($value)
{
  var_dump($value);
});
