<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

use app\admin\model\Admin as AdminModel;

use app\admin\model\Role  as RoleModel;

use app\admin\model\Node  as NodeModel;


class Rbac extends Auth
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
		$this->admin =new AdminModel();
		$this->role =new RoleModel();
		$this->node =new NodeModel();

	}
	//角色列表
	public function rolelist()
	{
		$this->Acquirerole();
		//查询role表显示role表信息
		$info =$this->role->select();
	  	$this->assign('info',$info);
	  	foreach($info as $vo)
	  {
	  	 $id =$vo['id'];
	  	 $info =$this->role->get($id);
	  	 $info->admin;
	  	 $role=$info->toArray();
	  	 $admin =$role['admin'];
	  	 $arr[] =$admin;
	  }
	  $this->assign('arr',$arr);
	  return $this->fetch();
	}
	//角色添加界面
	public function roleaddinfo()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
		$id =$_GET['id'];
		$result =$this->admin->select($id);
		$info =$this->role->select();
	  	$this->assign('info',$info);
		$this->assign('result',$result[0]);
		return $this->fetch();

		}
		
	}
	//角色添加
	public function roleadd()
	{
		$this->Acquirerole();
		$uid =$_POST['uid'];
		$rolename =$_POST['rolename'];
		$status =$_POST['status'];
		$remark =$_POST['remark'];
		$result =$this->role->get(['name'=>$rolename]);
		$id =$result['id'];
		$info =$this->admin->get($uid);
		$info->role;
		$role =$info->toArray();
		$arr=[];
		foreach($role['role'] as $k=>$v)
		{
			$arr[]=$v['name'];
		}
		if(in_array($rolename,$arr))
		{
			$this->error('角色已存在，不允许重复添加');
		}else{
				if($result)
			{
				$info =$this->admin->get($uid);
				$result =$info->role()->save($id);
				if($result)
				{
					$this->success('角色添加成功','admin/rbac/userlist');
				}else{
					$this->error('角色添加失败');
				}
			}else{
				$info =$this->admin->get($uid);
				$result = $info->role()->save(['name'=>$rolename,'status'=>$status,'remark'=>$remark]);
				if($result)
				{
					$this->success('角色添加成功','admin/rbac/userlist');
				}else{
					$this->error('角色添加失败');
				}
			}

		}
	
	}
	//管理员列表
	public function userlist()
	{
		$this->Acquirerole();
		$info =$this->admin->select();
	  	$this->assign('info',$info);
	  	foreach($info as $vo)
	  {
	  	 $id =$vo['id'];
	  	 $info =$this->admin->get($id);
	  	 $info->role;
	  	 $admin=$info->toArray();
	  	 $role =$admin['role'];
	  	 $arr[] =$role;
	  }
	  $this->assign('arr',$arr);
	  return $this->fetch();
	}
	//删除用户并删除用户所对应的角色
	public function userdelete()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
		$id =$_GET['id'];
		$info=$this->admin->get($id);
		//删除中间表
		for($i =0;$i<count($info->role);$i++)
		{
			$info->role()->detach([$info->role[$i]['id']]);
		}
		//删除用户信息
		if($this->admin->destroy($id))
		{
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}

		}
		
	}
	//权限列表
	public function nodelist()
	{
		$this->Acquirerole();
		$info =$this->node->select();
	  	$this->assign('info',$info);
	  	foreach($info as $vo)
	  {
	  	 $id =$vo['id'];
	  	 $info =$this->node->get($id);
	  	 $info->role;
	  	 $node=$info->toArray();
	  	 $role =$node['role'];
	  	 $arr[] =$role;
	  }
	  $this->assign('arr',$arr);
		return $this->fetch();
	}
	//权限添加页面
	public function nodeaddinfo()
	{
		$this->Acquirerole();
		if(!empty($_GET)){
		$id =$_GET['id'];
		$result =$this->role->select($id);
		$this->assign('result',$result[0]);
		return $this->fetch();

		}
		
	}
	//权限添加
	public function nodeadd()
	{
		
		$this->Acquirerole();
		if(!empty($_POST)){
		$rid =$_POST['rid'];
		$nodename =$_POST['nodename'];
		$info =$this->role->get($rid);
		$info->node;
		$node=$info->toArray();
		if($this->deep_in_array($nodename, $node['node'])){
			$this->error('权限已存在，不允许重复添加');
		}else{

		$status =$_POST['status'];
		$result =$this->node->get(['title'=>$nodename]);
		if(!empty($result))
		{
			$id =$result['id'];
			$info =$this->role->get($rid);
			$result =$info->node()->save($id);
			if($result)
			{
				$this->success('权限添加成功','rolelist');
			}else{
				$this->error('权限添加失败');
			}

			}else{
			$info =$this->role->get($rid);
			$result = $info->node()->save(['title'=>$nodename,'status'=>$status]);
			if($result)
			{
				$this->success('权限添加成功','rolelist');
			}else{
				$this->error('权限添加失败');
			}

		}

		}		

		}
		
		
		/**/
		
		//只增加中间表
	}
	//检验值是否在一个二维数组中
 	public function deep_in_array($value, $array) 
 	{
	    foreach($array as $item) {   
	        if(!is_array($item)) {   
	            if ($item == $value) {  
	               return true;
  
	            } else {  
	                continue;   
	            }  
	        }   
	            
	        if(in_array($value, $item)) {  
	           return true;    
	        } else if($this->deep_in_array($value, $item)) {  
	            return true;      
	        }  
		    }   
		    return false;   
	}
	public function userdetail()
	{
		return $this->fetch();
	}
	//删除权限并删除中间表
	public function nodedelete()
	{
		$nodeid =$this->request->param('nodeid');
		$info=$this->node->get($nodeid);
		//删除中间表
		for($i =0;$i<count($info->role);$i++)
		{
			$info->role()->detach([$info->role[$i]['id']]);
		}
		//删除用户信息
		if($this->node->destroy($nodeid))
		{
			echo json_encode(['code'=>1,'Msg'=>'删除成功']);

		}else{
			echo json_encode(['code'=>2,'Msg'=>'删除失败']);
		}
	}
	//删除角色并删除中间表
	public function roledelete()
	{
		$roleid =$this->request->param('roleid');
		$info =$this->role->get($roleid);
		for($i =0;$i<count($info->node);$i++)
		{
			$info->node()->detach([$info->node[$i]['id']]);
		}
		for($i =0;$i<count($info->admin);$i++)
		{
			$info->admin()->detach([$info->admin[$i]['id']]);
		}
		//删除用户信息
		if($this->role->destroy($roleid))
		{
			echo json_encode(['code'=>1,'Msg'=>'删除成功']);

		}else{
			echo json_encode(['code'=>2,'Msg'=>'删除失败']);
		}


		/*$info =$this->role->get($roleid);
		//删除中间表
		dump($info);*/
	}


}