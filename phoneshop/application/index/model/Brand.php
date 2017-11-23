<?php
namespace app\index\model;
use think\Model;

class Brand extends Model
{
    //查询所有品牌
    public function brand()
    {
        return $this->where('pid','<>',8)->where('pid','<>','9')->select();
    }
    //查询所有品牌(取前五)
    public function brand5()
    {
        return $this->where('pid','<>',8)->where('pid','<>','9')->limit(5)->select();
    }

}