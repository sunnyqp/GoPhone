<?php
namespace app\admin\model;

use think\Model;
class Role extends Model
{
	//角色表绑定用户表
	public function admin()
	{
		return $this->belongsToMany('admin','role_admin','user_id','role_id');
	}
	public function node()
	{
		return $this->belongsToMany('node','access','node_id','role_id');
	}
}