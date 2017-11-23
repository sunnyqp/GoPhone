<?php
namespace app\admin\model;

use \think\Model;

use \think\Db;

use traits\model\SoftDelete;

class Phoneinfo extends model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	public function phoneinfo()
	{
		
		/*return Db::query("SELECT gp_brand.title,gp_phoneinfo.name,gp_phoneinfo.color,gp_phoneinfo.stock,gp_phoneinfo.money,gp_phoneinfo.picture,gp_phoneinfo.ishot,gp_phoneinfo.addtime from gp_brand INNER JOIN gp_phoneinfo ON gp_phoneinfo.pid = gp_brand.pid ");*/
		return Db::field('brand.title,phoneinfo.name,phoneinfo.color,phoneinfo.stock,phoneinfo.money,phoneinfo.picture,phoneinfo.ishot,phoneinfo.addtime')->table('gp_brand','gp_phoneinfo')->join('gp_phoneinfo.pid = gp_brand.pid')->select();
	}
	public function brand()
	{
		return  $this->belongsTo('Brand','pid');
	}
	public function pidlist()
	{
		
	}
	public function pidinfo($pid)
	{
		return $this->where('pid',$pid)->select();
	}
	public function phonelike($content)
	{
		return $this->whereLike('name',"%$content%")->select();
	}
	public function productinfo($gid)
	{
		return Db::query("SELECT gp_brand.title,gp_phoneinfo.name,gp_phoneinfo.color,gp_phoneinfo.stock,gp_phoneinfo.money,gp_phoneinfo.picture,gp_phoneinfo.ishot,gp_phoneinfo.addtime,gp_phoneinfo.ram,gp_phoneinfo.camera,gp_phoneinfo.cell,gp_phoneinfo.system,gp_phoneinfo.nuclear,gp_phoneinfo.size,gp_phoneinfo.gid from gp_brand INNER JOIN gp_phoneinfo ON gp_phoneinfo.pid = gp_brand.pid where gp_phoneinfo.gid =$gid ");
	}
	

}