<?php
namespace app\index\model;
use think\Model;
use think\Db;

class Pinglun extends Model
{
    //可直接插入create_time
    protected $autoWriteTimestamp = true;

    //查询所有评论每页5条数据
    public function pl($id)
    {
        return Db::name('pinglun')->where('gid',$id)->paginate(5);
    }
    //插入评论表
    public function tian($id,$pinglun,$username,$photo)
    {
        return $this->save(['gid'=>$id,'pinglun'=>$pinglun,'username'=>$username,'photo'=>$photo]);
    }
}