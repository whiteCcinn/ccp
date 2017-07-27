<h1 align="center">ccp ---PHP实现Promise+协程调用---- </h1>

PHP + Coroutine + Promise

# 说明

在PHP当中，我们或多或少会使用到异步编程的思想，但是异步编程难免给我们带来回调地狱的感觉，并且代码可读性十分之差，在ES6规范里面，或者更早就可以推出了一个做Promise的东西，利用Promise，你可以用同步的做法来实现异步的操作，代码可读性上大大提高，不仅如此，由于异步编程和异步IO的混合使用，导致代码的准确性难以提高，不可否认的是，Promise的推出，大大的提高了你们编写异步代码的可靠性，虽然这也是会损耗一些十分微小的性能，但是任何取舍都是相对的。


# 参考

- [ECMAScript 2015 (6th Edition, ECMA-262) Promise](http://www.ecma-international.org/ecma-262/6.0/#sec-promise-objects)
- [ECMAScript Latest Draft (ECMA-262)Promise](https://promisesaplus.com/)

# 支持PromiseAPI

1. `promise->then`
2. `primise->catch`
3. `Promise::all`
4. `Promise::race`
5. `Promise::resolve`
6. `Promise::reject`
7. `Promise::warp`
8. `Promise::co`
9. `等等`
