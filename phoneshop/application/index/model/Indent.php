<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Indent extends Model
{
    //添加订单
    public function tian($username,$address,$shou,$gid,$money,$num,$beizhu,$dingnum,$time,$phone)
    {
        return $this->save(['username'=>$username,'address'=>$address,'consignee'=>$shou,'gid'=>$gid,'payable'=>$money,'number'=>$num,'remark'=>$beizhu,'dingnum'=>$dingnum,'create_time'=>$time,'phone'=>$phone]);
    }
    //添加订单
    public function tian1($username,$address,$shou,$name,$money,$num,$beizhu,$dingnum,$time,$phone)
    {
        return $this->save(['username'=>$username,'address'=>$address,'consignee'=>$shou,'gid'=>$name,'payable'=>$money,'number'=>$num,'remark'=>$beizhu,'dingnum'=>$dingnum,'create_time'=>$time,'phone'=>$phone]);
    }
    //查询用户所有订单
    public function dingdan($username)
    {
        return Db::name('indent')->where('username',$username)->order('id','desc')->paginate(5);
    }

}