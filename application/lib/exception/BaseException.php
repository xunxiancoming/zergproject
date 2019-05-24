<?php


namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    //http 状态码
    public $code = 400;

    //错误信息提示
    public $msg = 'param error';

    //自定义错误码
    public $errorCode = 10000;
}