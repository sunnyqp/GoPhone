<?php
namespace app\admin\model;

use think\Model;

class Siteinfo extends Model
{
	public function siteinfo()
	{
		return $this->select();
	}
}