<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;

class Aside extends Auth
{
	protected $is_login =['*'];

	protected $admin;
	protected $role;
	protected $node;

	public function _initialize()
	{
		if(!$this->checklogin() && in_array('*',$this->is_login)){
			$this->error('没有登录请登录','admin/auth/login');
		}
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
	}
	//判断角色
	public function asides()
	{
		$this->Acquirerole();
		return $this->fetch();
	}
}