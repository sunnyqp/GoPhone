<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;

use app\admin\model\Pinglun as PinglunModel;

class Pinglun extends Auth
{
	protected $is_login =['*'];
	protected $admin;
	protected $role;
	protected $node;
	protected $pinglun;
	public function _initialize()
	{
		if(!$this->checklogin() && in_array('*',$this->is_login)){
			$this->error('没有登录请登录','admin/auth/login');
		}
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
		$this->pinglun =new PinglunModel();
	}
	//评论信息展示
	public function plinfo()
	{
		$this->Acquirerole();
		$list =$this->pinglun->paginate(3);
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//评论软删除
	public function plsoftdelete()
	{
		$this->Acquirerole();
		$id =$this->request->param('pid');
		if(!empty($id)){
			if($this->pinglun->destroy($id)){

  				echo json_encode(['code'=>1,'Msg'=>'删除成功']);

			}else{

				echo json_encode(['code'=>2,'Msg'=>'删除失败']);
			}
		}else{
			$this->error('尚未选中不能删除');
		}		}
}