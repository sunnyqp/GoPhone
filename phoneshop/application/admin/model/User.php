<?php
namespace app\admin\model;

use \think\Model;

class User extends Model
{
	//统计今日注册会员数
	public function usertol()
	{
		date_default_timezone_set("Asia/Shanghai");   
		$time=date("Y-m-d 00:00:00");
		$news=strtotime($time);
		return $this->where("create_time",'>',$news)->count('id');
	}
}