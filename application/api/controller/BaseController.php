<?php


namespace app\api\controller;


use app\api\service\Token as TokenService;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Controller;

class BaseController extends Controller
{
    // 用户权限检测
    protected function checkPrimaryScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if($scope){
            if($scope >= ScopeEnum::user){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    // CMS管理员禁止权限检测
    protected function checkExclusiveScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');

        if($scope){
            if($scope == ScopeEnum::user){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
}