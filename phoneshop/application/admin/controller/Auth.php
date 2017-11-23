<?php
namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;

use think\Controller;

use \think\Validate;

use \think\Session;

class Auth extends Controller
{
	//验证是否登录
	protected $is_login =[''];
	protected $admin;
	protected $role;
	protected $node;
	public function _initialize()
	{
		if(!$this->checklogin() && in_array('*',$this->is_login)){
			$this->error('没有登录请登录','admin/auth/login');
		}
		$this->admin = new AdminModel();
		$this->role = new RoleModel();
		$this->node =new NodeModel();
	}
	public function login()
	{
		return $this->fetch();
	}
	public function checklogin()
	{
		return session('?id');
	}
	//登录
	public function dologin()
	{
		$captcha =$this->request->param('yzm');
		$validate = new Validate([
		'captcha'=>'require|captcha',
		]);
		$data = [
		'captcha'=>$captcha,
		];
		if (!$validate->check($data)) {
		$info = $validate->getError();
		echo header("refresh:0;url=login");
		exit("<script>alert('$info');</script>");
		}else{
			$username = $this->request->param('username');
			$password = $this->request->param('password');
			$res =$this->admin->admininfo($username,$password);
			if($res){
				Session::set('id',$res['id']);
				Session::set('username',$res['username']);
				$this->success('登录成功',url('admin/admin/index'));

			}else{
				$this->error('登录失败',url('admin/auth/login'));

			}

		}
	}
	//查找用户权限
	public function Toallocate($rid)
	{
		//用户角色
		$roleinfo =$this->role->get($rid);
		$roleinfo->node;
		$node=$roleinfo->toArray();
	  	$nodes =$node['node'];
	  	$arr =[];
	  	foreach($nodes as $v)
	  	{
	  		$arr[] =$v['title'];
	  	}
	  	$this->assign('nodes',$arr);
	}
	//查找用户的所有角色
	public function Acquirerole()
	{
		if (Session::get('id'))
		{
			$id =Session::get('id');
			$info=$this->admin->get($id);
			$info->role;
			$role =$info->toArray();
			$roles =$role['role'];
			$arr =[];
			foreach($roles as $v)
			{
				$arr[] =$v['name'];
			}
			Session::set('arr',$arr);
			$this->assign('roles',$arr);
		}
	}
	//统计访问量
	public function tongji()
	{
		$file = dirname(__FILE__).'/tongji.db';
		$fp=fopen($file,'r+');
		$content='';
		if (flock($fp,LOCK_EX)){
		while (($buffer=fgets($fp,1024))!=false){
		$content=$content.$buffer;
		}
		//$data=unserialize($content);
		$data = unserialize(file_get_contents($file));
		//设置记录键值
		$total = 'total';
		$month = date('Ym');
		$today = date('Ymd');
		$yesterday = date('Ymd',strtotime("-1 day"));
		$tongji = array();
		// 总访问增加
		$tongji[$total] = $data[$total] + 1;
		// 本月访问量增加
		$tongji[$month] = $data[$month] + 1;
		
		// 今日访问增加
		$tongji[$today] = $data[$today] + 1;
		//保持昨天访问
		$tongji[$yesterday] = $data[$yesterday];

		//保存统计数据
		ftruncate($fp,0); // 将文件截断到给定的长度
		rewind($fp); // 倒回文件指针的位置
		fwrite($fp, serialize($tongji));
		flock($fp,LOCK_UN);
		fclose($fp);

		//输出数据
		$total = $tongji[$total];
		$month = $tongji[$month];
		$today = $tongji[$today];
		$yesterday = $tongji[$yesterday]?$tongji[$yesterday]:0;
		$this->assign('total',$total);
		$this->assign('month',$month);
		$this->assign('today',$today);
		$this->assign('yesterday',$yesterday);

		
		}
	}

}