<?php
namespace app\index\model;
use think\Model;
use think\Db;

class Liulan extends Model
{
    //查询该用户所有浏览历史对应所有手机信息
    public function lishi($username)
    {
        return Db::name('liulan')->alias('l')->join('phoneinfo', 'l.gid =phoneinfo.gid')->order('l.id','desc')->where('l.username',$username)->paginate(8);
    }
    //删除记录
    public function shanjilu($id,$username)
    {
        return $this->where('id',$id)->where('username',$username)->delete();
    }
    //查询是否有此浏览记录
    public function islishi($id,$username)
    {
        return $this->where('gid',$id)->where('username',$username)->select();
    }
    //插入浏览历史
    public function addlishi($username,$id)
    {
        return $this->insert(['username'=>$username,'gid'=>$id]);
    }
}