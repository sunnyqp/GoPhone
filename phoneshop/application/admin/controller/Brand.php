<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Phoneinfo as PhoneModel;

use app\admin\model\Brand     as BrandModel;

use app\admin\model\Category  as CategoryModel;

use app\admin\model\Admin     as AdminModel;

use app\admin\model\Role      as RoleModel;

use app\admin\model\Node      as NodeModel;

use \think\Db;

use \org\Upload;

class Brand extends Auth
{
	protected $is_login =['*'];
	protected $product;
	protected $brand;
	protected $category;
	protected $admin;
	protected $role;
	protected $node;
	public function _initialize()
	{
		if(!$this->checklogin() && in_array('*',$this->is_login)){
			$this->error('没有登录请登录','admin/auth/login');
		}
		$this->product =new PhoneModel();
		$this->brand = new BrandModel();
		$this->category =new CategoryModel();
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();

	}
	//商品详情
	public function productlist()
	{
		$this->Acquirerole();
		$pidlist =$this->brand->pidlist();
		$this->assign('pidlist',$pidlist);
		$list =$this->product->order('update_time desc')->paginate(5);
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
		
	}
	//选择品牌后展示商品信息
	public function pidinfo()
	{
		$this->Acquirerole();
		$info=$this->request->get();
		if(!empty($info['pname'])&&$info['pname']!='品牌')
		{
			$title = $info['pname'];
			$res =$this->brand->pidinfo($title);
			$pid = $res['0']['pid'];
			$list=$this->product->pidinfo($pid);
			$this->assign('list',$list);
			return $this->fetch('pidlist');
		}else{
			$this->Acquirerole();
			$pidlist =$this->brand->pidlist();
			$this->assign('pidlist',$pidlist);
			$list =$this->product->paginate(5);
			//获取分页显示
			$page = $list->render();
			$this->assign('list',$list);
			$this->assign('page',$page);
			return $this->fetch('pidlist');
			}

	}
	//单个商品信息
	public function productdetail()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
		$gid =$_GET['gid'];
		$result =$this->product->productinfo($gid);
		$this->assign('result',$result);
		return $this->fetch();
		}
		
	}
	//商品信息修改
	public function upload(){
		$this->Acquirerole();
		$file = request()->file('image');
		if(!empty($file)){
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if($info){
			$url = "../../../../uploads/".$info->getSaveName();
			$picture =str_replace('\\','/',$url);
			$gid     = $_POST['gid'];
			$name    = $_POST['name'];
			$color   = $_POST['color'];
			$ram     = $_POST['ram'];
			$camera  = $_POST['camera'];
			$cell    = $_POST['cell'];
			$system  = $_POST['system'];
			$nuclear = $_POST['nuclear'];
			$size    = $_POST['size'];
			$money   = $_POST['money'];
			$stock   = $_POST['stock'];
			$ishot   = $_POST['ishot'];
			$result =$this->product->save(['name'=>$name,'color'=>$color,'ram'=>$ram,'camera'=>$camera,'cell'=>$cell,'system'=>$system,'nuclear'=>$nuclear,'size'=>$size,'money'=>$money,'stock'=>$stock,'picture'=>$picture,'ishot'=>$ishot],['gid'=>$gid]);
			if($result){
				$this->success('修改成功', 'productlist');
			}else{
				$this->error('修改失败');
			}
			}else{
			// 上传失败获取错误信息
			$this->error($file->getError());
			}
		}else{
			$gid =$_POST['gid'];
			$result =$this->product->save($_POST,['gid',$gid]);	
			if($result){
				$this->success('修改成功', 'productlist');
			}else{
				$this->error('修改失败');
			}

		}
	}
	//商品信息软删除
	public function softdelete()
	{
		$this->Acquirerole();
		if(!empty($_GET)){

			$gid =$_GET['gid'];
			if($this->product->destroy($gid)){

  				$this->success('删除成功');

			}else{

				$this->error('删除失败');
			}
		}else{
			$this->error('尚未选中不能删除');
		}

	}
	//商品回收站
	public function recyle()
	{
		$this->Acquirerole();
		$pidlist =$this->brand->pidlist();
		$this->assign('pidlist',$pidlist);
		$list =$this->product->onlyTrashed()->order('update_time desc')->paginate(3);
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}
	//商品软删除恢复
	public function recover()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
			$gid =$_GET['gid'];
			$result =$this->product->save(['delete_time'=>null],['gid'=>$gid]);
			if($result){
				$this->success('商品信息已恢复','productlist');
			}else{
				$this->error('修改失败');
			}

		}
		
	}
	//商品删除
	public function delete()
	{
		$this->Acquirerole();
		$gid =$_GET['gid'];
		$result =$this->product->destroy($gid,true);
		if($result){
			$this->success('商品删除');
		}else{
			$this->error('修改失败');
		}

	}
	//显示商品分类
	public function category()
	{
		$this->Acquirerole();
		$data =$this->category->field('*,concat(path,',',id) ')->order('path')->select();
		foreach($data as $k=>$v){
			$data[$k]['name'] =str_repeat("|---",$v['level']).$v['name'];
		}
		$this->assign('data',$data);
		return $this->fetch();
	}
	//添加商品分类
	public function categoryadd()
	{
		$this->Acquirerole();
		//无限级分类添加分类
		$data['name'] = $_POST['name'];
		$data['pid']  = $_POST['pid'];
		if($data['name']!=" " && $data['pid'] !=0){
			$path = $this->category->field("path")->find($data['pid']);
			$data['path']=$path['path'];
			$data['level'] =substr_count($data['path'],',');
			$re =$this->category->save($data);
			$id =$this->category->id;
			$path['path'] =$data['path'].','.$id;
			$path['level'] =substr_count($path['path'],",");
			$result =$this->category->save(['path'=>$path['path'],'level'=>$path['level']]);
			if($result){
				$this->success('添加成功','category');
			}else{
				$this->error('添加失败');
			}

		}else if($data['name']!="" && $data['pid'] ==0){
			$data['path']=$data['pid'];
			$data['level'] =1;

			$re =$this->category->save($data);
			$id =$this->category->id;
			$path['path'] =$data['path'].','.$id;
			//dump($path['path']);
			$result =$this->category->save(['path'=>$path['path'],'level'=>$data['level']]);
			if($result){
				$this->success('添加成功','category');
			}else{
				$this->error('添加失败');
			}
			
		}else{

			$this->error('添加失败，内容不能为空');

		}	
	}
	//商品分类删除
	public function categorydelete()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
			$id =$_GET['id'];
			$data =$this->category->where("pid",$id)->find();
			if($data){
				$this->error('分类下面还有子分类，不允许删除','category');
			}else{
				$re =$this->category->where('id',$id)->delete($id);
				if($re){
					$re =$this->success('删除成功','category');
				}else{
					$this->error('删除失败','category');
				}
			}

		}
		
	}
	public function productlists()
	{
		$this->Acquirerole();
		 return $this->fetch();
	}
	//商品分类选框
	public function productaddlist()
	{
		$this->Acquirerole();
		//商品分类选框
		$data =$this->category->field('*,concat(path,',',id) ')->order('path')->select();
		foreach($data as $k=>$v){
			$data[$k]['name'] =str_repeat("|---",$v['level']).$v['name'];
		}
		$this->assign('data',$data);
		return $this->fetch();	
	}
	//商品添加
	public function productadd()
	{
		$this->Acquirerole();
		if(!empty($_POST)&&!empty($_POST['name']))
		{
				$file = request()->file('image');
			if(!empty($file)){
				$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
				$url = "../../../../uploads/".$info->getSaveName();
				$picture =str_replace('\\','/',$url);
				$name    = $_POST['name'];
				$color   = $_POST['color'];
				$ram     = $_POST['ram'];
				$camera  = $_POST['camera'];
				$cell    = $_POST['cell'];
				$system  = $_POST['system'];
				$nuclear = $_POST['nuclear'];
				$size    = $_POST['size'];
				$pid     = $_POST['pid'];
				$money   = $_POST['money'];
				$stock   = $_POST['stock'];
				$ishot   = $_POST['ishot'];
				$result =$this->product->save(['name'=>$name,'color'=>$color,'ram'=>$ram,'camera'=>$camera,'cell'=>$cell,'system'=>$system,'nuclear'=>$nuclear,'size'=>$size,'pid'=>$pid,'money'=>$money,'stock'=>$stock,'picture'=>$picture,'ishot'=>$ishot]);
				if($result){
					$this->success('新增成功', 'productlist');
				}else{
					$this->error('新增失败');
				}
				}else{
				// 上传失败获取错误信息
				$this->error($file->getError());
				}
			}else{
				$result =$this->product->save($_POST);	
				if($result){
					$this->success('新增成功', 'productlist');
				}else{
					$this->error('新增失败');
				}

			}

		}else{
			$this->error('信息不完全，请填写完整商品信息');
		}


	}
	
}