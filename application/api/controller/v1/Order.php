<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\Order as OrderValidate;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

class Order extends BaseController
{
    //逻辑分析
    //用户在选择商品后，向服务器API接口提交包含他所选择的商品的信息
    //API在接收到信息后，需要检查订单相关商品的库存量
    //有库存，把订单数据存入数据库中，下单成功了，返回客户端可以支付了的消息
    //调用我们的支付接口，进行支付
    //还需要再次检查库存量
    //库存量充足，服务器这边可以调用微信的支付接口进行支付
    //微信会返回一个支付结果（异步）
    //成功：也需要进行库存量的检查
    //成功：进行库存量的扣除

    protected $beforeActionList = [
        'checkExclusiveScope'=>['only'=>'placeOrder'],
        'checkPrimaryScope'=>['only'=>'getSummaryByUser,getDetail']
    ];

    public function placeOrder()
    {
        (new OrderValidate())->gocheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }

    public function getSummaryByUser($page=1,$size=15)
    {
        (new PagingParameter())->gocheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrder = OrderModel::getSummaryByUser($uid,$page,$size);
        if($pagingOrder->isEmpty()){
            return [
                'data'=>[],
                'current_page'=>$pagingOrder->getCurrentPage()
            ];
        }
        $data = $pagingOrder->toArray();
        return [
            'data'=>$data,
             'current_page'=>$pagingOrder->getCurrentPage()
        ];
    }

    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->gocheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }
}