<?php


namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule =[
        'id'=>'require|isPositiveInteger',
    ];

    protected $message = [
        'id.require'=>'id参数缺失',
        'id.isPositiveInteger'=>'id必须是正整数',
    ];
}