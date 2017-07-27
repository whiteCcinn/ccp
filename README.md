<h1 align="center">ccp ---PHP implements a Promise + schedule call---- </h1>

PHP + Coroutine + Promise

# specification

In PHP, we will use more or less to the asynchronous programming, but it brings us a callback asynchronous programming hell feeling, and the code readability is very poor, in the ES6 specification, or earlier can launch a Promise something, you can use Promise to implement asynchronous synchronous approach the operation, code readability greatly improved, not only that, due to the mixing of asynchronous programming and asynchronous IO use, cause it is difficult to improve the accuracy of code, it is undeniable that the introduction of Promise, greatly improves the reliability of the code you write asynchronous, although this is the loss of some very small performance, but any the choice is relative.


# reference

- [ECMAScript 2015 (6th Edition, ECMA-262) Promise](http://www.ecma-international.org/ecma-262/6.0/#sec-promise-objects)
- [ECMAScript Latest Draft (ECMA-262)Promise](https://promisesaplus.com/)

# Support Promise-API

1. `promise->then`
2. `primise->catch`
3. `Promise::all`
4. `Promise::race`
5. `Promise::resolve`
6. `Promise::reject`
7. `Promise::warp`
8. `Promise::co`
9. `more`

# Translation switch

- [中文翻译.MD](https://github.com/whiteCcinn/ccp/edit/master/ZH-README.md)
