<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Lb as LbModel;

use app\admin\model\Phoneinfo as PhoneinfoModel;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node as NodeModel;

class Picture extends Auth
{
	protected $is_login =['*'];
	protected $lb;
	protected $phoneinfo;
	protected $admin;
	protected $role;
	protected $node;

	public function _initialize()
	{
		$this->lb =new LbModel();
		$this->phoneinfo =new PhoneinfoModel();
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
	}
	//轮播图列表
	public function lbpicturelist()
	{
		$this->Acquirerole();
		$list =$this->lb->lbinfo();
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//轮播图单张列表
	public function lbpictureinfo()
	{
		$this->Acquirerole();
		if(!empty($_GET))
		{
			$id =$_GET['id'];
			$result =$this->lb->where('id',$id)->find();
			$data = $this->phoneinfo->column('name');
			$this->assign('data',$data);
			$this->assign('result',$result);
			return $this->fetch();
		}
	}
		
	//修改轮播图
	public function lbpictureupload()
	{
		$this->Acquirerole();
		$file = request()->file('image');
		if(!empty($file))
		{
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if($info){
			$url = "../../../../uploads/".$info->getSaveName();
			$urlnew =str_replace('\\','/',$url);
			}else{
			echo $file->getError();
			}
			$con  = $_POST['con'];
			$gid  =$_POST['gid'];
			$id   = $_POST['id'];
			$result =$this->lb->save(['url'=>$urlnew,'con'=>$con,'gid'=>$gid],['id'=>$id]);
			if($result){
				$this->success('修改成功','lbpicturelist');
			}else{
				$this->error('修改失败','lbpictureinfo');
			}		
		}else{
			$con  = $_POST['con'];
			$gid  =$_POST['gid'];
			$id   = $_POST['id'];
			$result =$this->lb->save(['con'=>$con,'gid'=>$gid],['id'=>$id]);
			if($result){
				$this->success('修改成功','lbpicturelist');
			}else{
				$this->error('修改失败','lbpictureinfo');
			}

		}	
	}
	//轮播图软删除
	public function lbsoft()
	{
		$this->Acquirerole();
		if(!empty($_GET)){

			$id =$_GET['id'];
			if($this->lb->destroy($id)){

  				$this->success('删除成功','lbrecyle');

			}else{

				$this->error('删除失败');
			}
		}else{
			$this->error('尚未选中不能删除');
		}
	}
	//轮播图回收站
	public function  lbrecyle()
	{
		$this->Acquirerole();
		$list =$this->lb->onlyTrashed()->paginate(3);
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//轮播图恢复
	public function lbrecover()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
			$id =$_GET['id'];
			$result =$this->lb->save(['delete_time'=>null],['id'=>$id]);
			if($result){
				$this->success('轮播图已恢复');
			}else{
				$this->error('修改失败');
			}

		}
		
	}
	//轮播图删除
	public function lbdelete()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
		$id =$_GET['id'];
		$result =$this->lb->destroy($id,true);
		if($result){
			$this->success('轮播图删除成功');
		}else{
			$this->error('删除失败');
		}

		}
		
	}
	//添加轮播图
	public function lbaddshow()
	{
		$this->Acquirerole();
		$result = $this->phoneinfo->select();
		$this->assign('result',$result);
		return $this->fetch();
	}
	public function lbadd()
	{

		$this->Acquirerole();
		$file = request()->file('image');
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
		$url = "../../../../uploads/".$info->getSaveName();
		$urlnew =str_replace('\\','/',$url);
		}else{
		echo $file->getError();
		}
		$con  = $_POST['con'];
		$gid = $_POST['gid'];
		$result =$this->lb->save(['url'=>$urlnew,'con'=>$con,'gid'=>$gid]);
		if($result){
			$this->success('添加成功','lbpicturelist');
		}else{
			$this->error('添加失败');
		}

	}
}
