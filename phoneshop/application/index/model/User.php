<?php
namespace app\index\model;

use \think\Model;
use \Db;
use traits\model\SoftDelete;

class User extends model
{
	//use SoftDelete;
	//protected $auto =['birthday'=>'1508219308'];
	public function logincheck($username,$password)
	{
		return $this->get(['username'=>$username,'password'=>$password]);
	}

	public function getStatusAttr($value){
		$status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
		return $status[$value];
	}
	public function getStatusTextAttr($value,$data)
	{
		$status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
		return $status[$data['status']];
	}
	public function setUsernameAttr($value)
	{
		return strtolower($value);
	}
	//一对一关联
	public function profile()
	{
		return $this->hasOne('profile' , 'user_id')->bind('adds');
	}
	//一对多关联
	public function arc()
	{
		return $this->hasMany('arc','u_id');
	}
	//多对对关联
	public function role()
	{
		return $this->belongsToMany('Role','user_role','r_id','u_id');
	}
	//查询登陆人的信息
    public function info($username)
    {
        return $this->where('username',$username)->select();
    }
    //修改密码
    public function gaimi($new,$username)
    {
        return $this->save(['password'=>"$new"],['username'=>"$username"]);
    }

}

