<?php

/**********************************************************\
 *                                                        *
 * ccp/Reject.php                                         *
 *                                                        *
 * The Reject state class                                 *
 *                                                        *
 * Author: Cai wenhui <471113744@qq.com>                  *
 *                                                        *
\**********************************************************/

namespace ccp;

class Reject
{
  private $_promise = null;

  public function __construct(Promise $promise)
  {
    $this->_promise = $promise;
  }

  function __invoke($reason)
  {
    if ($reason === $this->_promise)
    {
      \call_user_func(new Reject($this->_promise), 'Conflict, solve itself');

      return;
    }

    if ($this->_promise->state === Promise::PENDING)
    {
      $this->_promise->state  = Promise::REJECTED;
      $this->_promise->reason = $reason;
      while (count($this->_promise->subscribers) > 0)
      {
        $subscriber = array_shift($this->_promise->subscribers);
        $this->_promise->privateReject(
            $subscriber['fnReject'],
            $subscriber['next'],
            $reason);
      }
    }
  }
}