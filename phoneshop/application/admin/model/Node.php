<?php
namespace app\admin\model;

use think\Model;
class Node extends Model
{
	//权限绑定角色表
	public function role()
	{
		return $this->belongsToMany('role','access','role_id','node_id');
	}
}