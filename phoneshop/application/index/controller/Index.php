<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Phoneinfo as Pinfo;
use app\index\model\Lb as Lunbo;
use app\index\model\Zixun as ZX;
use app\index\model\User as Us;
use app\index\model\Category as Cg;
use app\index\model\Siteinfo as Sf;

use think\Session;
use api\Xiaohua;

class Index extends Controller
{
    protected $Lb;
    protected $Pinfo;
    protected $Zixun;
    protected $Us;
    protected $Xiaohua;
    protected $Cg;
    protected $Sf;

    public function _initialize()
    {
        $this->Lb = new Lunbo();
        $this->Pinfo = new Pinfo();
        $this->Zixun = new Zx();
        $this->Us = new Us();
        $this->Cg = new Cg();
        $this->Sf = new Sf();

        $this->Xiaohua = new Xiaohua();
    }

    public function index()
    {
        //站点信息
        $this->siteinfo();
        //笑话
        //$this->xiaohua();
        //分类
        $big = $this->Cg->big();
        $small = $this->Cg->small();

        if(!empty($big)){
            $this->assign('big',$big);
        }
        if(!empty($small)){
            $this->assign('small',$small);
        }

        //轮播图
        $lb = $this->Lb->lb();
        //热卖手机取5个
        $hotinfo = $this->Pinfo->hotinfo();
        //全部热卖手机
        $allhotinfo = $this->Pinfo->allhotinfo();
        //最新手机信息
        $newinfo = $this->Pinfo->newinfo();
        //所有黑科技
        $heiinfo = $this->Pinfo->heiinfo();
        //黑科技（6个）
        $hei6info = $this->Pinfo->hei6info();
        //所有平板
        $pinginfo = $this->Pinfo->pinginfo();
        //平板（6个）
        $ping6info = $this->Pinfo->ping6info();
        //咨询
        $zixun = $this->Zixun->zixun();

        //echo $this->Lb->getLastSql();
        //echo $this->Pinfo->getLastSql();
        if(!empty(Session::get('username'))){
            $username = Session::get('username');
            //头像
            $info = $this->Us->info($username)[0]->toArray();
            $photo = $info['userpic'];
            $this->assign('photo',$photo);
            $this->assign('lb',$lb);
            $this->assign('hotinfo',$hotinfo);
            $this->assign('allhotinfo',$allhotinfo);
            $this->assign('newinfo',$newinfo);
            $this->assign('heiinfo',$heiinfo);
            $this->assign('hei6info',$hei6info);
            $this->assign('pinginfo',$pinginfo);
            $this->assign('ping6info',$ping6info);
            $this->assign('zixun',$zixun);


            return $this->fetch();
        }else{
            $this->assign('photo','__IMG__/UserHead1.jpg');
            $this->assign('lb',$lb);
            $this->assign('hotinfo',$hotinfo);
            $this->assign('allhotinfo',$allhotinfo);
            $this->assign('newinfo',$newinfo);
            $this->assign('heiinfo',$heiinfo);
            $this->assign('hei6info',$hei6info);
            $this->assign('pinginfo',$pinginfo);
            $this->assign('ping6info',$ping6info);
            $this->assign('zixun',$zixun);

            return $this->fetch();
        }

    }
    //站点信息
    public function siteinfo()
    {
        $info = $this->Sf->info()[0]->toArray();
        //dump($info);
        $this->assign('sitename',$info['sitename']);
        $this->assign('webname',$info['websitename']);
        $this->assign('url',$info['url']);
        $this->assign('beian',$info['record']);
    }
    //退出
    public function tuichu()
    {
        session(null);
        echo header("refresh:0;url=../../");
    }
    //改密码后
    public function gaimihou()
    {
        session(null);
        echo header("refresh:0;url=../user/login");
    }
    //联系我们
    public function lianxi()
    {
        echo header("refresh:0;url=/");
        echo "<script>alert('站长手机号：17611050532!');</script>";
    }
    //笑话
    public function xiaohua()
    {
        $appkey = '3037c2bc6d981569';//你的appkey
        $pagenum = 1;
        $pagesize = 1;
        $sort = 'rand';//addtime/rand
        $url = "http://api.jisuapi.com/xiaohua/text?pagenum=$pagenum&pagesize=$pagesize&sort=$sort&appkey=$appkey";

        $result = $this->Xiaohua->curlOpen($url);

        $jsonarr = json_decode($result, true);
//exit(var_dump($jsonarr));
        if ($jsonarr['status'] != 0) {
            echo $jsonarr['msg'];
            exit();
        }
        $result = $jsonarr['result'];

//            echo $result['total'].' '.$result['pagesize'].' '.$result['pagenum'].'<br>';
        $this->assign('xiaohua',$result['list']);
    }
//    //分类的连接
//    public function fenlei()
//    {
//        if(!empty($_GET)){
//            $name = $_GET['name'];
//            $fenlei = $this->Pinfo->fenlei($name);
//            dump($fenlei);
//        }
//    }
}
