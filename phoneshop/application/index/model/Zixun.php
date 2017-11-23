<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Zixun extends Model
{
    public function zixun()
    {
        //查询所有咨询
        return Db::name('zixun')->paginate(5);
    }
    //查询三条咨询
    public function zi3xun()
    {
        return $this->limit(3)->select();
    }
    //查询四条咨询
    public function zi4xun()
    {
        return $this->limit(4)->select();
    }
    //查询所有对应传过来的id的内容
    public function zixuninfo($id)
    {
        return $this->where('id',$id)->select();
    }
    //传过来id-1的内容
    public function zixunup($idup)
    {
        return $this->where('id',$idup)->select();
    }
    //传过来id+1的内容
    public function zixundown($iddown)
    {
        return $this->where('id',$iddown)->select();
    }
    //点击量
    public function dianji($click,$id){
        return $this->where('id',$id)->update(['click'=>$click]);
    }

}