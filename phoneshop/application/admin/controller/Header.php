<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use \think\Session;


class Header extends Auth
{
	protected $is_login =['*'];
	protected $mesg;
	public function _initialize()
	{
		if(!$this->checklogin() && in_array('*',$this->is_login)){
			$this->error('没有登录请登录','admin/auth/login');
		}
	}
	public function headerinfo()
	{
		return $this->fetch();
	}
	public function tuichu()
	{
		session(null);
		$this->success('用户安全退出','admin/auth/login');
		return $this->fetch();
	}
}