<?php
namespace app\admin\controller;

use app\admin\controller\Auth as AuthModel;

use app\admin\model\Siteinfo as SiteinfoModel;

use app\admin\model\User  as UserModel;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;

use app\admin\model\Indent as IndentModel;

use \think\Session;

class Admin extends AuthModel
{
	protected $is_login =['*'];
	protected $user;
	protected $site;
	protected $brand;
	protected $admin;
	protected $role;
	protected $node;
	protected $indent;
	public function _initialize()
	{
		if(!$this->checklogin() && in_array('*',$this->is_login)){
			$this->error('没有登录请登录','admin/auth/login');
		}
		$this->site =new SiteinfoModel();
		$this->user =new UserModel();
		$this->admin=new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
		$this->indent =new IndentModel();
	}
	public function index()
	{	//判断用户角色
		$this->Acquirerole();
		//获取今日统计数据
		//今日订单数
		$indentcou =$this->indent->cou();
		//今日销售额
		$indentsal =$this->indent->sal();
		//今日销售商品总数
		$salestotal =$this->indent->tol();
		//今日注册会员数
		$usertotal =$this->user->usertol();
		$this->assign('indentcou',$indentcou);
		$this->assign('indentsal',$indentsal);
		$this->assign('salestotal',$salestotal);
		$this->assign('usertotal',$usertotal);
		return $this->fetch();
	}
	public function siteinfo()
	{
		//判断用户角色
		$this->Acquirerole();
		$result =$this->site->siteinfo();
		$this->assign('info',$result['0']);
		return $this->fetch();
	}
	//清除缓存
	public function clear()
	{
	 if($this->request->param('type')){
	 	$type =$this->request->param('type');
	 	$name =explode('_',$type);
	 	$count =count($name);
	 	for($i =0;$i<$count;$i++){
	 		$abs_dir=dirname(dirname(dirname(dirname(__FILE__))));
	 		$pa=$abs_dir.'\runtime\\';
    		$runtime=$abs_dir.'indexRuntime~runtime.php';
    		if(file_exists($runtime))//判断 文件是否存在
		    {
		     unlink($runtime);//进行文件删除
		    
	 		}
	 		  $this->rmFile($pa,$name[$i]); 
  			}
  			  echo json_encode(['code'=>1,'Msg'=>'删除成功']);
			  }else{
			    echo json_encode(['code'=>0,'Msg'=>'删除失败']);
			  }
	 }
	public function rmFile($path,$fileName)
	{  
			$path = preg_replace('/(\/){2,}|{}{1,}/','/',$path); 
				  //得到完整目录 
				  $path.=$fileName;
				  //判断此文件是否为一个文件目录
				  if(is_dir($path)){
				   //打开文件
				   if ($dh = opendir($path)){
				    //遍历文件目录名称
				     while (($file = readdir($dh)) != false){
				      //逐一进行删除
				      unlink($path.''.$file);
			     		 }
			      	//关闭文件
			     	 closedir($dh);
			   		 } 
		   		}
	}
	//修改站点信息
	public function sitechange()
	{
		$sitename =$_POST['sitename'];
		$websitename =$_POST['websitename'];
		$url =$_POST['url'];
		$record =$_POST['record'];
		$is_close =$_POST['is_close'];
		$id =$_POST['id'];
		$result =$this->site->save(['sitename'=>$sitename,'websitename'=>$websitename,'url'=>$url,'record'=>$record,'is_close'=>$is_close],['id'=>$id]);
		if($result)
		{
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}


	


 }







