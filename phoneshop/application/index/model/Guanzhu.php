<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Guanzhu extends Model
{
    //用户关注的所有产品
    public function guanzhu($username)
    {
        return Db::name('guanzhu')->alias('g')->join('phoneinfo', 'g.gid =phoneinfo.gid')->order('g.id','desc')->where('g.username',$username)->paginate(8);
    }
    //添加关注
    public function addguanzhu($username,$id)
    {
        return $this->insert(['gid'=>$id,'username'=>$username]);
    }
    //查询该用户是否关注了该产品
    public function isguanzhu($id,$username)
    {
        return $this->where('gid',$id)->where('username',$username)->select();
    }
    //删除关注
    public function shanguanzhu($username,$id)
    {
        return $this->where('gid',$id)->where('username',$username)->delete();
    }
}