<?php


namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule =[
        'ids'=>'require|checkIDs'
    ];

    protected $message = [
        'ids.require'=>'ids参数缺失',
        'ids.checkIDs'=>'ids必须是以逗号分隔的正整数',
    ];

    public function checkIDs($value)
    {
        $value = explode(',',$value);
        if(empty($value)){
            return false;
        }
        foreach ($value as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }else{
                return true;
            }
        }
    }
}