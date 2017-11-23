<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Indent as IndentModel;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;

class Indent extends Auth
{
	protected $is_login =['*'];
	protected $indent;
	protected $admin;
	protected $role;
	protected $node;
	public function _initialize()
	{
		$this->indent =new IndentModel();
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
	}
	//订单列表
	public function indentlist()
	{
		$this->Acquirerole();
		$list =$this->indent->indentlist();
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//订单删除
	public function indentdelete()
	{
		$this->Acquirerole();
		$id =$this->request->param();
		if(!empty($id)){
			if($this->indent->indentdelete($id['oid'])){
				echo json_encode(['code'=>1,'Msg'=>'删除成功']);
				

			}else{

				echo json_encode(['code'=>2,'Msg'=>'删除失败']);
			}
		}else{
			$this->error('尚未选中不能删除');
		}	
	}
	//订单详情列表
	public function indentdetail()
	{
		$this->Acquirerole();
		if(!empty($_GET))
		{
			$id =$_GET['oid'];
			$result =$this->indent->indentinfo($id);
			$this->assign('result',$result);
			return $this->fetch();
		}
		
	}
	//修改订单状态为已付款
	public function indentfk()
	{
		$this->Acquirerole();
		$oid =$this->request->param('oid');
		if(!empty($oid)){
			$this->indent->save(['is_express'=>'1'],['id'=>$oid]);
			echo json_encode(['code'=>0,'Msg'=>'修改成功']);
		}else{
			echo json_encode(['code'=>1,'Msg'=>'修改失败']);
		}
	}
	//修改订单状态为已发货
	public function indentfh()
	{
		$this->Acquirerole();
		$oid =$this->request->param('oid');
		if(!empty($oid)){
			$this->indent->save(['is_express'=>'2'],['id'=>$oid]);
			echo json_encode(['code'=>0,'Msg'=>'修改成功']);
		}else{
			echo json_encode(['code'=>1,'Msg'=>'修改失败']);
		}
	}
	//修改订单状态为已收货
	public function indentsh()
	{
		$this->Acquirerole();
		$oid =$this->request->param('oid');
		if(!empty($oid)){
			$this->indent->save(['is_express'=>'3'],['id'=>$oid]);
			echo json_encode(['code'=>0,'Msg'=>'修改成功']);
		}else{
			echo json_encode(['code'=>1,'Msg'=>'修改失败']);
		}
	}
	//修改订单状态为已完成
	public function indentqr()
	{
		$this->Acquirerole();
		$oid =$this->request->param('oid');
		if(!empty($oid)){
			$this->indent->save(['is_express'=>'4'],['id'=>$oid]);
			echo json_encode(['code'=>0,'Msg'=>'修改成功']);
		}else{
			echo json_encode(['code'=>1,'Msg'=>'修改失败']);
		}
	}
}