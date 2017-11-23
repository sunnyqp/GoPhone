<?php
namespace app\admin\model;

use think\Model;

use \think\Db;

class Brand extends Model
{
	public function phoneinfo()
	{
		return $this->hasMany('Phoneinfo','pid');
	
	}
	public function pidlist()
	{
		return $this->select();
	}
	public function pidinfo($title)
	{
		return  $this->where('title',$title)->select();
	}
	
	
}