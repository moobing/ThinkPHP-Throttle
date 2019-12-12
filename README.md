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
	->middleware(mbing\middleware\Throttle::class,'60,1');

```
## 过程
1.使用ThinkPHP官方的cache缓存操作，对访问该接口时，post参数携带token，则使用md5(接口路由|token)当做缓存key，未携带token的则使用md5(接口路由|用户IP地址)当做缓存key，推荐使用redis
2.接口返回格式：因API接口开发友好需要，本人使用自己封装的统一输出格式（已注释，可以根据自己需要进行fork改动），默认使用抛出异常方式进行返回提示

```php
// 输出json格式
// return error('请求频繁,请'.($data[0]/60).'分钟后再试（请勿尝试访问，将重新计算等待时间）');
// 抛出异常
throw new Exception('请求频繁,请'.($data[0]/60).'分钟后再试（请勿尝试访问，将重新计算等待时间）');

```

## json格式封装方法
如需使用统一的json格式，可以在APP应用根目录下的common.php文件增加以下代码，开启输出json格式，注释抛出异常即可使用（以下封装根据自己实际需要进行改动）

application/common.php
```php

// 统一封装API接口结果输出
function result($data = '',$code = 200,$message = '成功',$name = 'content')
{
    if($code == false){
        $json = $data;
    }else{
        $json['status'] = $code;
        $json['message'] = $message;
        $data?$json[$name] = $data:'';
    }
	return json($json)->options(['json_encode_param' => JSON_UNESCAPED_SLASHES]);
}
// 统一封装API接口错误输出
function error($message = '失败',$code = 404,$data = '',$name = 'content')
{
    if($code == false){
        $json = $data;
    }else{
        $json['status'] = $code;
        $json['message'] = $message;
        $data?$json[$name] = $data:'';
    }
    return json($json)->options(['json_encode_param' => JSON_UNESCAPED_SLASHES]);
}

```