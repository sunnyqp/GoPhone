<?php
namespace app\admin\model;

use think\Model;

use traits\model\SoftDelete;

class Lb extends Model
{
	use SoftDelete;
	protected $autoWriteTimestamp = true;
	//轮播图展示
	public function lbinfo()
	{
		return $this->order('update_time desc')->paginate(5);
	}
}