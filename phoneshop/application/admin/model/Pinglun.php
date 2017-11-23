<?php
namespace app\admin\model;

use \think\Model;

use \think\Db;

use traits\model\SoftDelete;

class Pinglun extends Model
{
	use SoftDelete;

	protected $autoWriteTimestamp = true;

	
}