<?php
namespace app\index\model;
use think\Model;
use think\Db;

class Category extends Model
{
    //大板块
    public function big()
    {
        return $this->where('pid',0)->select();
    }
    //小板块
    public function small()
    {
        return $this->where('pid','<>',0)->select();
    }

}