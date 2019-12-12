Throttle Middleware
===================

ThinkPHP5.1 & ThinkPHP6.0 Throttle Middleware(TP5、TP6可用的接口节流中间件)

## 安装

```
composer require mbing/throttle
```


## route.php DEMO(在route/route.php中使用中间件)
middleware(参数一,参数二) 的第二个参数表示“60秒,1次”(默认值)，即限制该路由地址1分钟内只可以重复请求1次，可以根据自己需要进行修改

```php
Route::rule('api/Controller/action','api/Controller/action')
	->middleware(mbing\sdk\middleware\Throttle::class,'60,1');

```