<?php


namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    public function getToken($code='')
    {
        (new TokenGet())->gocheck();
        $userToken = new UserToken($code);
        $token = $userToken->get();
        return [
            'token'=>$token
        ];
    }
}