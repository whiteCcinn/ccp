<?php

/**********************************************************\
 *                                                        *
 * ccp/Promise.php                                        *
 *                                                        *
 * Promise Main Class                                     *
 *                                                        *
 * Author: Cai wenhui <471113744@qq.com>                  *
 *                                                        *
\**********************************************************/

namespace ccp;

class Promise
{

  // 默认态
  CONST PENDING = 0;

  // 完成态
  CONST FULFILLED = 1;

  // 失败态
  CONST REJECTED = 2;

  public $subscribers = [];

  // 当前对象状态
  public $state = self::PENDING;

  // 当前对象的值
  public $value = null;

  // 当前对象Reject的理由
  public $reason = null;

  // 解决
  private $resolve = null;

  // 拒绝
  private $reject = null;

  /**
   * Promise constructor.
   *
   * @param null $computation
   *   function($resolve,$reject){...}
   */
  public function __construct($computation = null)
  {
    $this->resolve = new Resolve($this);
    $this->reject  = new Reject($this);
    if (is_callable($computation))
    {
      $computation(...[$this->resolve, $this->reject]);
    }

    return $this;
  }

  /**
   * Resolve入口
   * @param         $fnFulfill
   * @param promise $next
   * @param         $x
   */
  public function privateResolve($fnFulfill, $next, $x)
  {
    if (is_callable($fnFulfill))
    {
      $this->privateCall($fnFulfill, $next, $x);
    } else
    {
      call_user_func($next->resolve, $x);
    }
  }

  /**
   * Reject入口
   * @param         $fnReject
   * @param Promise $next
   * @param         $e
   */
  public function privateReject($fnReject, $next, $e)
  {
    if (is_callable($fnReject))
    {
      $this->privateCall($fnReject, $next, $e);
    } else
    {
      call_user_func($next->reject, $e);
    }
  }

  /**
   * 实际调用
   * @param         $callback
   * @param Promise $next
   * @param         $x
   */
  private function privateCall($callback, $next, $x)
  {
    try
    {
      $r = $callback(...[$x]);
      call_user_func($next->resolve, $r);
    } catch (\Exception $e)
    {
      call_user_func($next->reject, $e);
    } catch (\Error $e)
    {
      call_user_func($next->reject, $e);
    }
  }

  /**
   * 注册函数
   *
   * @param null|\Closure $fnFulfill 当前Promise对象执行成功的时候的回调函数
   * @param null|\Closure $fnReject 当前Promise对象执行失败的时候的回调函数
   *
   * @return Promise 新的一个Promise对象
   */
  public function then($fnFulfill/*Success callback*/, $fnReject = null/*Fail callback*/)
  {
    if (!is_callable($fnFulfill))
    {
      $fnFulfill = null;
    }
    if (!is_callable($fnReject))
    {
      $fnReject = null;
    }
    $promise = new Promise();
    if ($this->state === self::FULFILLED)
    {
      $this->privateResolve($fnFulfill, $promise, $this->value);
    } elseif ($this->state === self::REJECTED)
    {
      $this->privateReject($fnReject, $promise, $this->reason);
    } else
    {
      array_push($this->subscribers, array(
          'fnFulfill' => $fnFulfill,
          'fnReject'  => $fnReject,
          'next'      => $promise
      ));
    }

    return $promise;
  }

  /**
   * 等价于then(null,$fnReject)
   *
   * @param $fnReject
   *
   * @return Promise
   */
  public function catch ($fnReject)
  {
    return $this->then(null, $fnReject);
  }

  /**
   *   =============================================静态方法区分==============================================
   */

  /**
   * 等价于如下代码：
   *  new Promise(function(resolve){
   *    resolve(5)
   *  })
   *
   * @return Promise
   */
  public static function resolve($value)
  {
    return new Promise(function ($resolve) use ($value)
    {
      $resolve($value);
    });
  }

  /**
   * 等价于如下代码：
   *  new Promise(function(null,reject){
   *    reject(5)
   *  })
   *
   * @return Promise
   */
  public static function reject($value)
  {
    return new Promise(function ($resolve = null, $reject) use ($value)
    {
      $reject($value);
    });
  }


  /**
   * 批量处理Promise
   *
   * @param $promiseList
   *
   * @return Promise
   */
  public static function all($promiseList)
  {
    $mainPromise = null;

    self::toPromise($promiseList)->then(function ($list) use (&$mainPromise)
    {
      $result = [];
      $break  = false;
      foreach ($list as $index => $promise)
      {
        if ($break) break;
        self::toPromise($promise)->then(function ($value) use ($index, &$result)
        {
          $result[ $index ] = $value;
        }, function ($value) use (&$break, &$mainPromise)
        {
          $break       = true;
          $mainPromise = self::reject($value);
        });
      }
      if (!$break)
        $mainPromise = self::resolve($result);
    });

    return $mainPromise;
  }

  /**
   * 批量处理Promise，当最早的对象改变时停止（可以理解为虽然是批量，但是有first AND once的特点）
   *
   * @param $promiseList
   *
   * @return Promise
   */
  public static function race($promiseList)
  {
    $mainPromise = null;

    self::toPromise($promiseList)->then(function ($list) use (&$mainPromise)
    {
      $result = [];
      $break  = false;
      foreach ($list as $index => $promise)
      {
        if ($break) break;
        $break = true;
        self::toPromise($promise)->then(function ($value) use ($index, &$result, &$mainPromise)
        {
          $mainPromise = self::resolve($value);
        }, function ($value) use (&$break, &$mainPromise)
        {
          $mainPromise = self::reject($value);
        });
      }
    });

    return $mainPromise;
  }

  /*=============================================  非Promise协议标准的辅助静态方法 ===================================*/

  /**
   * 判断是否是Promise对象
   *
   * @param $obj
   *
   * @return bool
   */
  public static function isPromise($obj)
  {
    return $obj instanceof Promise;
  }

  /**
   * 把所有数据都进行Promise化
   *
   * @param $obj
   *
   * @return Promise
   */
  public static function toPromise($obj)
  {
    if (self::isPromise($obj))
    {
      return $obj;
    }
    if ($obj instanceof \Generator)
    {
      return self::co($obj);
    }

    return self::resolve($obj);
  }

  /**
   * Promisory 生产一个将会生产Promise的函数（某种意义上，一个Promise生产函数可以被看做一个“Promise工厂“）
   */
  public static function warp()
  {

  }

  /*=============================================== 用同步的写法写异步代码 ============================================*/

  /**
   * Promise + 协程调度 （此用法非标准Promise用法，因为标准的Promise只进行一次解析）
   *
   * @param $generator
   *
   * @return Promise
   */
  public static function co($generator)
  {
    if (is_callable($generator))
    {
      $args      = array_slice(func_get_args(), 1);
      $generator = call_user_func_array($generator, $args);
    }

    if (!($generator instanceof \Generator))
    {
      return self::toPromise($generator);
    }

    $promise = new Promise();

    // 递归fnFulfill回调函数
    $fnFulfill = function ($value) use (&$fnFulfill, &$fnReject, $generator, $promise)
    {
      try
      {
        $next = $generator->send($value);
        if ($generator->valid())
        {
          self::toPromise($next)->then($fnFulfill, $fnReject);
        } else
        {
          if (method_exists($generator, "getReturn"))
          {
            $ret = $generator->getReturn();
            call_user_func($promise->resolve, $ret);
          } else
          {
            call_user_func($promise->resolve, $value);
          }
        }
      } catch (\Exception $e)
      {
        call_user_func($promise->reject, $e);
      } catch (\Error $e)
      {
        call_user_func($promise->reject, $e);
      }
    };

    // 递归fnRejected回调函数
    $fnReject = function ($err) use (&$fnFulfill, $generator, $promise)
    {
      try
      {
        $fnFulfill($generator->throw($err));
      } catch (\Exception $e)
      {
        call_user_func($promise->reject, $e);
      } catch (\Error $e)
      {
        call_user_func($promise->reject, $e);
      }
    };

    // 开始Promise协程调度
    self::toPromise($generator->current())->then($fnFulfill, $fnReject);

    return $promise;
  }
}