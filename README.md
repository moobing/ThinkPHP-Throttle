Throttle Middleware
===================

ThinkPHP5.1 & ThinkPHP6.0 Throttle Middleware(TP5、TP6可用的接口节流中间件)

## 安装

```
composer require mbing/throttle
```


## route.php DEMO(在route/route.php中使用中间件)

```php
Route::rule('api/Controller/action','api/Controller/action')
	->middleware(mbing\sdk\middleware\Throttle::class,'60,1');

```