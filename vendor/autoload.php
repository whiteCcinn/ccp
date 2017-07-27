<?php
/**********************************************************\
 *                                                        *
 * autoload.php                                           *
 *                                                        *
 * autoload ccp\Promise(*) class                          *
 *                                                        *
 * Author: Cai wenhui <471113744@qq.com>                  *
 *                                                        *
\**********************************************************/

const _NAMESPACE = 'ccp';

// 自动加载
spl_autoload_register('_autoload', false, true);

function _autoload($className)
{
  $prefix       = _NAMESPACE . '\\';
  $prefixLength = strlen($prefix);

  $file = '';
  if (0 === strpos($className, $prefix))
  {
    $file = explode('\\', substr($className, $prefixLength));
    $file = implode(DIRECTORY_SEPARATOR, $file) . '.php';
  }

  $path = __DIR__ . DIRECTORY_SEPARATOR . _NAMESPACE . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $file;

  if (is_file($path))
  {
    require_once $path;
  } else
  {
    echo $path . PHP_EOL;
    throw new Exception('Autoload Fail');
  }
}