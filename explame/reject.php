<?php
require_once 'include.php';

ccp\Promise::reject(new Exception('error'))->catch(function($value)
{
  var_dump($value->getMessage());
});