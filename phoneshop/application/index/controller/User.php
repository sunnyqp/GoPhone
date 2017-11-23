<?php
namespace app\index\controller;
use app\index\model\User as UserModel;
use open\Open51094;
use app\index\model\Userinfo;
use  \think\Controller;
use  \think\Validate;
use \think\Db;
use \think\Session;
use api\Ucpaas;
use app\index\model\Category as Cg;
class User extends Controller
{
	protected $user;
	protected $userinfo;
	protected $Cg;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->Cg = new Cg();
		//$this->userinfo =new Userinfo();
	}
	//登陆
	public function login()
	{
		return $this->fetch();
	}
	//注册
	public function register()
	{
		return $this->fetch();
	}
	//第三方
	public function disanfang()
	{
		$open= new Open51094();
		$code =$_GET['code'];
		$res =$open->me($code);
		//获取第三方信息
		//dump($res);
		$name = $res['name'];
		$img  = $res['img'];
		$sex  = $res['sex'];
		$uniq = $res['uniq'];
		$from = $res['from'];
		//判断用户是否已用第三方登录过
		$res =Db::name('user')->where(['uniq'=>$uniq,'status'=>0])->find();
		//dump($res);
        if($res){
			$this->user->save(['login_time'=>time(),'username'=>$name],['uniq'=>$uniq]);
            Session::set('username',$name);
            echo header("refresh:0;url='../../'");
		}else{
			//把第三方信息存入数据库
			$this->user->save(['username'=>$name,'password'=>1,'create_time'=>time(),'userpic'=>$img,'uniq'=>$uniq,'type'=>$from,'sex'=>$sex]);
            Session::set('username',$name);
            echo header("refresh:0;url='../../'");
		}
		//dump($name);
	}

	public function logcheck()
    {
        $username = $this->request->param('phone');
        $password = md5($this->request->param('pwd'));
        if ($this->user->logincheck($username, $password)) {
            echo json_encode(['State' => 0, 'Msg' => '登陆成功']);
            Session::set('username', $username);
        } else {
            echo json_encode(['State' => 1, 'Msg' => '密码错误']);
        }
    }

    //注册
    public function reginfo()
    {

        $code =$this->request->param('messageCode');
        $yzm = Session::get('yzm');

        if($yzm == $code)
        {
            $phone =$this->request->param('phone');
            $pwd =md5($this->request->param('pwd'));
            $regip =$_SERVER['REMOTE_ADDR'];

            if($regip =='::1'){
                $regip ='127.0.0.1';
            }
            $ip = ip2long($regip);

            $result=$this->user->where('username',$phone)->find();

            if($result)
            {
                echo json_encode(['code'=>4,'Msg'=>'手机号码已注册,您可以直接登录']);die;
            }else{
                $result =$this->user->save(['username'=>$phone,'password'=>$pwd,'create_time'=>time()]);

                if($result)
                {
                    Session::set('username',$phone);
                    echo json_encode(['code'=>5,'Msg'=>'注册成功']);
                }else{
                    echo json_encode(['code'=>6,'Msg'=>'注册失败']);
                    exit;
                }
            }
        }else{
            echo json_encode(['code'=>3,'Msg'=>'验证码验证失败']);die;
        }
    }

    //短信验证
    public function zhpass()
    {
        //初始化必填
        $options['accountsid']='b5abedbc2e022e35ae808228a5f3c291'; //填写自己的
        $options['token']='1f80da8f17eeb677140b1fe85231d8b7'; //填写自己的
        //初始化 $options必填
        $this->uc = new Ucpaas($options);

        //随机生成6位验证码
        $nums = '0123456789';
        $yzm = substr(str_shuffle($nums),0,4);
        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $appId = "8a97e0816df14bd490b5711eef88709b";  //填写自己的
        $to = $this->request->param('phone');
        //dump($to);
        $templateId = "153040";
        $param="$yzm";
        $arr=$this->uc->templateSMS($appId,$to,$templateId,$param);
        if (substr($arr,21,6) == 000000) {
            //如果成功就，这里只是测试样式，可根据自己的需求进行调节
            echo json_encode(['code'=>1,'Msg'=>'短信验证码已发送成功，请注意查收短信']);
            Session::set('yzm',$yzm);
            Session::set('phone',$to);
        }else{
            //如果不成功
            echo json_encode(['code'=>0,'Msg'=>'短信验证码发送失败，请联系客服']);

        }
    }

    //找回密码界面
    public function forgetpwd()
    {
        return $this->fetch();
    }

    //找回密码验证
    public function forgetpwdcheck()
    {
        //判断用户是否存在
        $phone =$this->request->param('phone');
        $result=$this->user->where('username',$phone)->find();
        if(empty($result)){
            echo json_encode(['code'=>0,'Msg'=>'手机号码不存在，非法用户不允许修改']);

        }else {
            //短信验证
            $code = $this->request->param('messageCode');
            $yzm = Session::get('yzm');
            if ($yzm != $code) {
                echo json_encode(['code' => 1, 'Msg' => '验证码验证失败']);
                exit;
            } else {
                //验证验证码
                $validate = new Validate([
                    'captcha' => 'require|captcha',
                ], [
                    'captcha' => '验证码输入错误',
                ]);
                $data = [
                    'captcha' => input('imgCode'),
                ];
                if (!$validate->check($data)) {
                    $Msg = $validate->getError();
                    echo json_encode(['code' => 2, 'Msg' => $Msg]);

                } else {
                    $phone = $this->request->param('phone');
                    $pwd = md5($this->request->param('pwd'));
                    $regip = $_SERVER['REMOTE_ADDR'];
                    if ($regip == '::1') {
                        $regip = '127.0.0.1';
                    }
                    $ip = ip2long($regip);
                    $result = $this->user->save(['username' => $phone, 'password' => $pwd, 'create_time' => time()]);
                    if ($result) {
                        Session::set('username', $phone);
                        echo json_encode(['code' => 3, 'Msg' => '修改成功']);
                    } else {
                        echo json_encode(['code' => 4, 'Msg' => '修改失败']);
                        exit;
                    }
                }
            }
        }
    }
}