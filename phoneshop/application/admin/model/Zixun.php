<?php
namespace app\admin\model;

use think\Model;

use traits\model\SoftDelete;

class Zixun extends Model
{
	use SoftDelete;
	protected $autoWriteTimestamp = true;

}