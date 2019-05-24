<?php


namespace app\api\service;


use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        //32个字符串组成的一组随机字符串
        $randChars = getRandChars(32);
        //用三组字符串，进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //satl 盐
        $salt = config('secure.token_salt');

        $result = md5($randChars.$timestamp.$salt);
        return $result;
    }

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }
    
    public static function getCurrentUid()
    {
        //Token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    //支付时检查是否是当前用户在支付订单
    public static function isValidOperate($checkedID)
    {
        if(!$checkedID){
            throw new Exception('必须传入一个被检查的ID');
        }
        $currentUID = self::getCurrentUid();
        if($checkedID == $currentUID){
            return true;
        }
        return false;
    }
}