<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Photo as PhotoModel;

use app\admin\model\Phoneinfo as PhoneinfoModel;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role as RoleModel;

use app\admin\model\Node as NodeModel;

class Photo extends Auth
{
	protected $is_login =['*'];

	protected $photo;

	protected $phoneinfo;

	protected $admin;
	protected $role;
	protected $node;

	public function _initialize()
	{
		$this->photo =new PhotoModel();
		$this->phoneinfo=new PhoneinfoModel();
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
	}
	public function photolist()
	{
		$this->Acquirerole();
		$list =$this->photo->photoinfo();
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	public function photoaddshow()
	{
		$this->Acquirerole();
		$data = $this->phoneinfo->column('name');
		$this->assign('data',$data);
		return $this->fetch();
	}
	//添加广告图片
	public function photoadd()
	{
			$this->Acquirerole();
			$gid =$_POST['gid'];
			$files = request()->file('image');
			if(!empty($files))
			{
				foreach($files as $file){
					// 移动到框架应用根目录/public/uploads/ 目录下
					$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
					if($info){
					$url = "../../../../uploads/".$info->getSaveName();
					$urlnew =str_replace('\\','/',$url);
						
					}else{
					// 上传失败获取错误信息
					echo $file->getError();
					}
				}
				$data =['gid'=>$gid,'url'=>$urlnew];
				for ($i=0; $i <count($files); $i++) 
					{
						$result = $this->photo->morec($data);	
					}
				if($result){
					$this->success('添加成功','photolist');
				}else{
					$this->error('添加失败');
				}		

			}else{
				$this->error('未上传图片');
			}
				
			
	}
	//软删除广告图片
	public function photosoft()
	{
		$this->Acquirerole();
		if(!empty($_GET)){

			$id =$_GET['id'];
			if($this->photo->destroy($id)){

  				$this->success('删除成功','photorecyle');

			}else{

				$this->error('删除失败');
			}
		}else{
			$this->error('尚未选中不能删除');
		}
	}
	//图片回收站
	public function photorecyle()
	{
		$this->Acquirerole();
		$list =$this->photo->photodeleteinfo();
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
}