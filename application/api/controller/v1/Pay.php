<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope'=>['only'=>'getPreOrder']
    ];

    public function getPreOrder($id='')
    {
        (new IDMustBePositiveInt())->gocheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    public function receiveNotify()
    {
        //通知频率为15/15/30/180/1800/1800/1800/1800/3600 单位：秒
        //1、检查库存量，超卖
        //2、更新订单status状态
        //3、减库存

        //如果成功处理，返回成功处理的消息，否则，返回没有成功的消息

        //特点：post ；xml；回调路由不能带?参数
        $notify = new WxNotify();
        $config = new \WxPayConfig();
        $notify->Handle($config);
    }
}