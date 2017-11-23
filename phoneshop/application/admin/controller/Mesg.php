<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Zixun as ZixunModel;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;

class Mesg extends Auth
{
	protected $is_login =['*'];
	protected $mesg;
	protected $admin;
	protected $role;
	protected $node;
	public function _initialize()
	{
		$this->mesg =new ZixunModel();
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
	}
	//资讯列表
	public function mesglist()
	{
		$this->Acquirerole();
		$list =$this->mesg->order('update_time desc')->paginate(5);
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//资讯详情
	public function mesginfo()
	{
		$this->Acquirerole();
		if(!empty($_GET)){

		$id =$_GET['id'];
		$result =$this->mesg->where('id',$id)->find();
		$this->assign('result',$result);
		return $this->fetch();

		}
		
	}
	//资讯修改
	public function mesgchange()
	{
		$this->Acquirerole();
		if(!empty($_POST)){
			$id =$_POST['id'];
			$title =$_POST['title'];
			$content =$_POST['editorValue'];
			$file = request()->file('image');
			if(!empty($file)){
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
				$url = $info->getSaveName();
				}else{
				echo $file->getError();
				}
				$result =$this->mesg->save(['title'=>$title,'content'=>$content,'photo'=>$url],['id'=>$id]);
				if($result){
					$this->success('修改成功','mesglist');
				}else{
					$this->error('修改失败');
				}

			}else{
				$id =$_POST['id'];
				$title =$_POST['title'];
				$content =$_POST['editorValue'];
				$result =$this->mesg->save(['title'=>$title,'content'=>$content],['id'=>$id]);
				if($result){
					$this->success('修改成功','mesglist');
				}else{
					$this->error('修改失败');
				}


			}

		}
		
	}
	//资讯添加页面展示
	public function mesgadd()
	{
		$this->Acquirerole();
		return $this->fetch();
	}
	//资讯添加
	public function mesgaddinfo()
	{
		$this->Acquirerole();
		if(!empty($_POST)){
			$title =$_POST['title'];
			$content =$_POST['editorValue'];
			$file = request()->file('image');
			if(!empty($file)){
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
				$url = "../../../../uploads/".$info->getSaveName();
				$urlnew =str_replace('\\','/',$url);
				}else{
				echo $file->getError();
				}
				$result =$this->mesg->save(['title'=>$title,'content'=>$content,'photo'=>$urlnew]);
				if($result){
					$this->success('添加成功','mesglist');
				}else{
					$this->error('添加失败');
				}

			}else{
				$title =$_POST['title'];
				$content =$_POST['editorValue'];
				$result =$this->mesg->save(['title'=>$title,'content'=>$content]);
				if($result){
					$this->success('添加成功','mesglist');
				}else{
					$this->error('添加失败');
				}


			}

		}
	}
	//资讯软删除
	public function mesgsoft()
	{
		$this->Acquirerole();
		if(!empty($_GET)){

			$id =$_GET['id'];
			if($this->mesg->destroy($id)){

  				$this->success('删除成功','mesgrecyle');

			}else{

				$this->error('删除失败');
			}
		}else{
			$this->error('尚未选中不能删除');
		}
	}
	//资讯回收站
	public function mesgrecyle()
	{
		$this->Acquirerole();
		$list =$this->mesg->onlyTrashed()->order('update_time desc')->paginate(3);
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//资讯恢复
	public function mesgrecover()
	{
		$this->Acquirerole();
		$id =$_GET['id'];
		$result =$this->mesg->save(['delete_time'=>null],['id'=>$id]);
		if($result){
			$this->success('轮播图已恢复');
		}else{
			$this->error('修改失败');
		}
	}
	//资讯删除
	public function mesgdelete()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
			$id =$_GET['id'];
			$result =$this->mesg->destroy($id,true);
			if($result){
				$this->success('轮播图删除成功');
			}else{
				$this->error('删除失败');
			}

		}
		
	}
	

}