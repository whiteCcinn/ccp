<?php

/**********************************************************\
 *                                                        *
 * ccp/Resolve.php                                        *
 *                                                        *
 * The Resolve state class                                *
 *                                                        *
 * Author: Cai wenhui <471113744@qq.com>                  *
 *                                                        *
\**********************************************************/

namespace ccp;

class Resolve
{
  private $_promise = null;

  public function __construct(Promise $promise)
  {
    $this->_promise = $promise;
  }

  function __invoke($value)
  {
    if ($value === $this->_promise)
    {
      \call_user_func(new Reject($this->_promise), 'Conflict, solve itself');

      return;
    }

    if ($this->_promise->state === Promise::PENDING)
    {
      $this->_promise->state = Promise::FULFILLED;
      $this->_promise->value = $value;
      while (count($this->_promise->subscribers) > 0)
      {
        $subscriber = array_shift($this->_promise->subscribers);
        $this->_promise->privateResolve(
            $subscriber['fnFulfill'],
            $subscriber['next'],
            $value);
      }
    }
  }
}