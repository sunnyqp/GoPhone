<?php
namespace app\admin\model;

use think\Model;

use traits\model\SoftDelete;

use \think\Db;

class Indent extends Model
{
	use SoftDelete;
	protected $autoWriteTimestamp = true;

	public function indentlist()
	{
		return $this->order('update_time desc')->paginate(5);

	}
	public function getStatusAttr($value){
		$status = [-1=>'待付款',0=>'待发货',1=>'已发货',2=>'已签收'];
		return $status[$value];
	}
	public function indentinfo($id)
	{
		return $list =Db::table('gp_indent')->join('gp_phoneinfo','gp_indent.gid =gp_phoneinfo.name')->where('id','=',$id)->select();
	}
	public function indentdelete($id)
	{
		return $this->where('id','=',$id)->delete();
	}
	//今日订单数
	public function cou()
	{
		date_default_timezone_set('PRC');   
		$time=date("Y-m-d 00:00:00");
		$news=strtotime($time);
		return $this->where("create_time",'>',$news)->count('id');
	}
	//今日销售额
	public function sal()
	{
		date_default_timezone_set('PRC');   
		$time=date("Y-m-d 00:00:00");
		$news=strtotime($time);
		return $this->where("create_time",'>',$news)->sum('payable');
	}
	//今日销售商品总数
	public function tol()
	{
		date_default_timezone_set('PRC');   
		$time=date("Y-m-d 00:00:00");
		$news=strtotime($time);
		return $this->where("create_time",'>',$news)->sum('number');
	}
	 
}