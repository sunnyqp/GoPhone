<?php
namespace app\admin\model;

use \think\Model;

use \think\Db;

use traits\model\SoftDelete;

class Photo extends Model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	public function photoinfo()
	{
 		return $list = Db::table('gp_photo')->alias('p')->join('phoneinfo ', 'p.gid =phoneinfo.gid')->where('p.delete_time','null')->paginate(5);
	}
	public function brand()
	{
		return $list = Db::table('gp_photo')->alias('p')->join('phoneinfo ', 'p.gid =phoneinfo.gid')->select();
	}
	public function morec($data)
	{
		return $result =Db::name('photo')->insert($data);
	}
	public function photodeleteinfo()
	{
		return $list = Db::table('gp_photo')->alias('p')->join('phoneinfo ', 'p.gid =phoneinfo.gid')->field('gp_photo.id,gp_photo.url,gp_photo.create_time,gp_photo.update_time,gp_photo.delete_time,gp_phoneinfo.name')->where('gp_photo.delete_time','<>','null')->paginate(3);
	}
}