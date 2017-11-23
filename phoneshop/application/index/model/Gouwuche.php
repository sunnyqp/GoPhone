<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Gouwuche extends Model
{
    protected $autoWriteTimestamp = true;

    //根据用户名查询购物车
    public function gouwuche($username)
    {
        return $this->where('username',$username)->select();
    }
    //加入购物车
    public function addgouwuche($gid,$name,$username,$money,$num)
    {
        return $this->insert(['gid'=>$gid,'name'=>$name,'username'=>$username,'money'=>$money,'num'=>$num]);
    }
    //移除id等于$id的物品
    public function delgouwuche($id)
    {
        return $this->where('id',$id)->delete();
    }
    //查询购物车总金额
    public function money($username)
    {
        return $this->where('username',$username)->sum('money');
    }
    //根据用户名两表联查
    public function gouwucheinfo($username)
    {
        return $this->view('gp_phoneinfo','*')
            ->view('gp_gouwuche','*','gp_phoneinfo.gid = gp_gouwuche.gid')
            ->where('gp_gouwuche.username',$username)
            ->select();
    }
    //查询所有订单金额
    public function allmoney($username)
    {
        return $this->where('username',$username)->sum('money');
    }
    //查询所有订单金额
    public function allnum($username)
    {
        return $this->where('username',$username)->sum('num');
    }
    //支付成功后清除相对于username的购物车
    public function delgw($username)
    {
        return $this->where('username',$username)->delete();
    }
}