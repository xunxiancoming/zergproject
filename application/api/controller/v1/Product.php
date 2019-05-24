<?php


namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    public function getNewProduct($count=15)
    {
        (new Count())->gocheck();
        $products = ProductModel::getNewProduct($count);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getProductsByCategoryId($id)
    {
        (new IDMustBePositiveInt())->gocheck();
        $products = ProductModel::getProductsByCategoryId($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getProductById($id)
    {
        (new IDMustBePositiveInt())->gocheck();
        $product = ProductModel::getOneProduct($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }
}