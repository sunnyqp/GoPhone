<?php
namespace app\index\model;
use think\Model;

class Photo extends Model
{
    //查询所有详情图
    public function photo()
    {
        return $this->select();
    }
    //查询对应产品的详情图
    public function idphoto($gid)
    {
        return $this->where('gid',$gid)->select();
    }
}