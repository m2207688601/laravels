<?php
namespace App\Extend\Send;
class Send{
		public function show($tel,$num){
            $content = "您的验证码是：【{$num}】。如需帮助请联系客服。";//
            $ch = curl_init();//初始化
           $arr= config('app.Send');
           $str="{$arr['url']}?account={$arr['username']}&password={$arr['pwd']}&mobile={$tel}&content={$content}";
           curl_setopt($ch,CURLOPT_URL, $str);
           curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             $info = curl_exec($ch);
             // var_dump($info);
           return $info;
            }
	}
  ?> 