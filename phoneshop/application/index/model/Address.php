<?php
namespace app\index\model;
use think\Model;

class Address extends Model
{
    //查询对应登陆人的所有收货地址
    public function addinfo($username)
    {
        return $this->where('username',$username)->select();
    }
    //查询对应登陆人的默认收货地址
    public function moreninfo($username)
    {
        return $this->where('username',$username)->where('ismoren',1)->select();
    }
    //删除对应id的收货地址
    public function del($id)
    {
        return $this->where('id',$id)->delete();
    }
    //添加收货地址
    public function tian($username,$shou,$province,$city,$area,$address,$phone)
    {
        return $this->save(['username'=>$username,'shou'=>$shou,'province'=>$province,'city'=>$city,'area'=>$area,'address'=>$address,'phone'=>$phone]);
    }
    //修改是否默认
    public function ismoren($id,$is)
    {
        return $this->save(['ismoren'=>$is],['id'=>$id]);
    }
    //设置默认后把其他所有的都不默认
        public function bumoren($id)
        {
            return $this->where('id','<>',$id)->update(['ismoren'=>0]);
        }
    //查询id对应的收货地址信息
    public function gaidizhi($id)
    {
        return $this->where('id',$id)->select();
    }
    //修改id对应的收货地址信息
    public function xiugaidizhi($id,$shou,$province,$city,$area,$address,$phone)
    {
        return $this->save(['shou'=>$shou,'province'=>$province,'city'=>$city,'area'=>$area,'address'=>$address,'phone'=>$phone],['id'=>$id]);
    }
}