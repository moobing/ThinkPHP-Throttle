<?php

namespace mbing\middleware;

use think\facade\Cache;
use think\facade\Config;

class Throttle
{
    /**
     * Handle an incoming request.
     *
     * @param  $request
     * @param  \Closure $next
     * @param  int $maxAttempts
     * @param  int $decayMinutes
     * @return mixed
     */
    public function handle($request, \Closure $next,$string = '60,1')
    {
        //获取传入数据
        $data = explode(',',$string);
        //获取当前参数
        $params = $request->param();
        //获取当前接口
        $baseurl = $request->baseurl();
        //获取当前IP地址
        $ip = $request->ip();
		// 携带token则设置md5(请求接口|用户token)
		if(isset($params['token'])){
			$cacheKey = md5($baseurl.'|'.$params['token']);
		}else{
		// 未携带token则设置md5(请求接口|用户ip)
			$cacheKey = md5($baseurl.'|'.$ip);
		}
		$throttle = Cache::get($cacheKey);
		if(!$throttle){
			$cacheValue['time'] = time();
			$cacheValue['number'] = 1;
			Cache::set($cacheKey,$cacheValue);
		}else{
			$throttle_time = time() - $throttle['time'];
			if($throttle_time < $data[0] && $throttle['number'] > $data[1]){
				return error('请求频繁,请'.($data[0]/60).'分钟后再试（请勿尝试访问，将重新计算等待时间）');
			}
			//每次请求都获取最新时间
			$cacheValue['time'] = time();
			// 使用首次记录时间，并增加请求次数记录
			// $cacheValue['time'] = $throttle['time' ];
			if($throttle_time >= $data[0]){
				$cacheValue['number'] = 1;
			}else{
				$cacheValue['number'] = $throttle['number']+1;
			}
			Cache::set($cacheKey,$cacheValue);
		}
        return $next($request);
    }
}
