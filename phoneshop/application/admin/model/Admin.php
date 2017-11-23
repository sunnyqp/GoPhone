<?php
namespace app\admin\model;

use \think\Model;

use traits\model\SoftDelete;

class Admin extends model
{
	use SoftDelete;
	public function admininfo($username,$password)
	{
		return $this->where(['username'=>$username,'password'=>$password])->find();
	}
	public function role()
	{
		return $this->belongsToMany('role','role_admin','role_id','user_id');
	}
	public function aaa($id)
	{
		return $this->where('id',$id)->select();
	}
}