<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Phoneinfo as Info;
use app\index\model\Zixun as Zx;
use app\index\model\Brand as Bd;
use app\index\model\Photo as Pt;
use app\index\model\Liulan as Ll;
use app\index\model\Guanzhu as Gz;
use app\index\model\Address as Ad;
use app\index\model\Indent as In;
use app\index\model\Category as Cg;
use app\index\model\Pinglun as Pl;
use app\index\model\User as Us;
use app\index\model\Gouwuche as Gw;
use think\Session;
use think\Db;

class phoneinfo extends Controller
{
    protected $Info;
    protected $Zx;
    protected $Bd;
    protected $Pt;
    protected $Ll;
    protected $Gz;
    protected $Ad;
    protected $In;
    protected $Cg;
    protected $Pl;
    protected $Us;
    protected $Gw;
    public function _initialize()
    {
        $this->Info = new Info();
        $this->Zx = new Zx();
        $this->Bd = new Bd();
        $this->Pt = new Pt();
        $this->Ll = new Ll();
        $this->Gz = new Gz();
        $this->Ad = new Ad();
        $this->In = new In();
        $this->Cg = new Cg();
        $this->Pl = new Pl();
        $this->Us = new Us();
        $this->Gw = new Gw();
    }
    public function phoneinfo()
    {
        if(!empty($_GET)){

            $id = $_GET['id'];
            if(empty($this->Info->pids($id))){
                echo header("refresh:0;url='../../'");
                exit("<script>alert('你这是什么操作！');</script>");
            }
            //根据gid查询评论表
            $pl = $this->Pl->pl($id);
            //echo $this->Pl->getlastsql();
            //dump($pl);
            $page = $pl->render();
            //dump($page);
            $page = str_replace('/index/phoneinfo/phoneinfo?',"/index/phoneinfo/phoneinfo?id=$id&",$page);
            if(!empty($pl)){
                $this->assign('pl',$pl);
                $this->assign('page',$page);
            }
            //手机详情信息
            $rel = $this->Info->pids($id);
            if($rel){
                $rel = $this->Info->pids($id)[0]->toArray();
            }
            //口碑排行
            $hot5info = $this->Info->hot5info();
            //资讯信息
            $zi4xun = $this->Zx->zi4xun();
            //热销推荐
            $hot3info = $this->Info->hot3info();
            //所有品牌取前五
            $brand5 = $this->Bd->brand5();
            //查询对应品牌的所有产品
            $tongid = $rel['pid'];
            $tongpai = $this->Info->tongpai($tongid);
            //查询详情
            $gid = $rel['gid'];
            $idphoto = $this->Pt->idphoto($gid);
            //插入浏览历史
            if(!empty(Session::get('username'))){
                $username = Session::get('username');
                $islishi = $this->Ll->islishi($id,$username);
                if(empty($islishi)){
                    $this->Ll->addlishi($username,$id);
                }
            }
            //查询该用户是否关注了该产品
            if(!empty($_GET) && !empty(Session::get('username'))){
                $id = $_GET['id'];
                $username = Session::get('username');
                $isguanzhu = $this->Gz->isguanzhu($id,$username);
                if(empty($isguanzhu)){
                    $isguanzhu = 0;
                }else{
                    $isguanzhu = 1;
                }
                $this->assign('isguanzhu',$isguanzhu);
            }
			//分类
			$big = $this->Cg->big();
			$small = $this->Cg->small();

			if(!empty($big)){
				$this->assign('big',$big);
			}
			if(!empty($small)){
				$this->assign('small',$small);
			}
            //dump($idphoto);
            $this->assign('idphoto',$idphoto);
            $this->assign('tongpai',$tongpai);
            $this->assign('brand5',$brand5);
            $this->assign('zi4xun',$zi4xun);
            $this->assign('hot3info',$hot3info);
            $this->assign('hot5info',$hot5info);
            $this->assign('name',$rel['name']);
            $this->assign('gid',$rel['gid']);
            $this->assign('color',$rel['color']);
            $this->assign('ram',$rel['ram']);
            $this->assign('camera',$rel['camera']);
            $this->assign('cell',$rel['cell']);
            $this->assign('system',$rel['system']);
            $this->assign('nuclear',$rel['nuclear']);
            $this->assign('size',$rel['size']);
            $this->assign('stock',$rel['stock']);
            $this->assign('money',$rel['money']);
            $this->assign('dazhe',$rel['dazhe']);
            $this->assign('picture',$rel['picture']);
            return $this->fetch();
        }else{
            echo header("refresh:0;url='../../'");
            exit("<script>alert('你这是什么操作！');</script>");
        }
    }
    //购买页
    public function lijigoumai()
    {
        //立即购买
        if((!empty($_POST) && !empty(Session::get('username')))){
            //dump($_POST);
            $username = Session::get('username');
            $num = $_POST['num'];
            $gid = $_POST['gid'];
            $mai = $_POST['mai'];
            //查库存
            $kucun = $this->Info->kucun($gid)[0]->toArray()['stock'];
            if($num>$kucun){
                echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$gid'");
                exit("<script>alert('抱歉！库存不足');</script>");
            }
            if($mai=='加入购物车'){
                echo header("refresh:0;url='../phoneinfo/addgouwuche?gid=$gid && num=$num'");
            }else{
                //查询收货地址信息
                $address = $this->Ad->addinfo($username);
                $gidinfo = $this->Info->gidinfo($gid);
                if(empty($address)){
                    echo header("refresh:0;url='../person/address'");
                    exit("<script>alert('请添加地址！');</script>");
                }
                //分类
                $big = $this->Cg->big();
                $small = $this->Cg->small();

                if(!empty($big)){
                    $this->assign('big',$big);
                }
                if(!empty($small)){
                    $this->assign('small',$small);
                }
                //dump($gidinfo);
                $this->assign('address',$address);
                $this->assign('gidinfo',$gidinfo);
                $this->assign('num',$num);
                return $this->fetch();
            }
            }else{
                echo header("refresh:0;url='../../'");
                exit("<script>alert('你这是什么操作！');</script>");
            }
    }

        //购物车页
        public function gouwuche()
    {
        if(!empty(Session::get('username'))){
            $username = Session::get('username');
            //查询对应的购物车
            $gouwuche = $this->Gw->gouwuche($username);
            $money = $this->Gw->money($username);
            //dump($money);
            //dump($gouwuche);
            $this->assign('gouwuche',$gouwuche);
            $this->assign('money',$money);
            return $this->fetch();
        }


    }
    //加入购物车
    public function addgouwuche()
    {
        if(!empty($_GET)){
            $username = Session::get('username');
            //dump($_GET);
            $gid = $_GET['gid'];
            $num = $_GET['num'];
            //查询gid对应产品信息
            //产品名字
            $name = $this->Info->gidinfo($gid)[0]->toArray()['name'];
            //金额
            $money = $this->Info->gidinfo($gid)[0]->toArray()['money']*$num;
            //加入购物车
            $this->Gw->addgouwuche($gid,$name,$username,$money,$num);
            echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$gid'");
            exit ("<script>alert('加入购物车成功！查看请到个人中心 - 我的购物车中查看');</script>");
        }
    }
    //从购物车里移除
    public function delgouwuche()
    {
        if($_GET){
            $id = $_GET['id'];
            $this->Gw->delgouwuche($id);
            echo header("refresh:0;url='../phoneinfo/gouwuche'");
            exit ("<script>alert('移除成功！');</script>");
        }
    }
    //点击购买用户没有登陆
    public function islogin()
    {
        echo header("refresh:0;url='../user/login'");
        echo "<script>alert('请先登录！');</script>";
    }
    //提交订单
    public function dingdan()
    {
        if(!empty(Session::get('username'))){
            $username = Session::get('username');
            if ($_POST) {
                $username = Session::get('username');

                $shou = $_POST['shou'];
                $gid = $_POST['gid'];
                $money = $_POST['money'];
                $num = $_POST['num'];
                $beizhu = $_POST['beizhu'];
                if(empty($_POST['xuanze'])){
                    echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$gid'");
                    echo "<script>alert('请选择地址！');</script>";die;
                }
                $address = $_POST['xuanze'];
                $address = $this->Ad->gaidizhi($address);
                //dump($address);
                $phone = $address[0]->toArray()['phone'];
                $address = $address[0]->toArray()['province'] . $address[0]->toArray()['city'] . $address[0]->toArray()['area'] . $address[0]->toArray()['address'];
                $dingnum = 'GP' . time();
                $time = time();
                //添加订单
                $dingdan = $this->In->tian($username,$address, $shou, $gid, $money, $num, $beizhu, $dingnum,$time,$phone);
                if($dingdan){
                    echo header("refresh:0;url='../person/dingdan'");
                    echo "<script>alert('恭喜！下单成功！');</script>";
                }else{
                    echo header("refresh:0;url='../.'");
                    echo "<script>alert('抱歉！下单失败！请重试！');</script>";
                }
            }
        }else{
            echo header("refresh:0;url='../user/login'");
            echo "<script>alert('请先登录！');</script>";
        }
    }
    //评论产品
    public function pinglun()
    {
        if(!empty(Session::get('username'))){
            if(!empty($_POST['pinglun'])){
                $id = $_POST['gid'];
                $pinglun = $_POST['pinglun'];
                $username = Session::get('username');
                $photo = $this->Us->info($username)[0]->toArray()['userpic'];
                $this->Pl->tian($id,$pinglun,$username,$photo);
                echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$id'");
                echo "<script>alert('评论成功！');</script>";
            }else{
                $id = $_POST['gid'];
                echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$id'");
                echo "<script>alert('内容不能为空！');</script>";
            }
        }else{
            echo header("refresh:0;url='../user/login'");
            echo "<script>alert('请先登陆！');</script>";
        }
    }
    //购物车订单
    public function fukuan()
    {
        $username = Session::get('username');
        //查询收货地址信息
        $address = $this->Ad->addinfo($username);
        $fukuan = $this->Gw->gouwucheinfo($username);
        //查询订单金额
        $allmoney = $this->Gw->allmoney($username);
        //所有的件数
        $allnum = $this->Gw->allnum($username);
        //dump($fukuan);
        //dump($allmoney);
        $this->assign('address',$address);
        $this->assign('fukuan',$fukuan);
        $this->assign('allmoney',$allmoney);
        $this->assign('allnum',$allnum);
        //分类
        $big = $this->Cg->big();
        $small = $this->Cg->small();

        if(!empty($big)){
            $this->assign('big',$big);
        }
        if(!empty($small)){
            $this->assign('small',$small);
        }

        return $this->fetch();
    }
    //购物车订单支付
    public function zhifu()
    {
        $username = Session::get('username');
        if(!empty($_POST)){
            //dump($_POST);
            //购物车所有提交到订单的商品
            $allname = $this->Gw->gouwuche($username);
            $all = [];
            foreach ($allname as $v)
            {
                $all[] = $v['name'];
                //$all[] = $v['gid'];
            }
            $name = implode(',',$all);
            //$gid = implode(',',$all);
            //dump($gid);
            //订单总金额
            $money = $_POST['money'];
            //地址
            $addressid = $_POST['xuanze'];
            $address = $this->Ad->gaidizhi($addressid)[0]->toArray();
            $address = $address['province'].$address['city'].$address['area'].$address['address'];
            //手机号
            $phone = $_POST['phone'];
            //收货人
            $shou = $_POST['shou'];
            //备注
            $beizhu = $_POST['beizhu'];
            //总数量
            $num = $_POST['num'];
            //订单号
            $dingnum = 'GP'.time();
            $time = time();

            //支付（插入订单表）
            $this->In->tian1($username,$address,$shou,$name,$money,$num,$beizhu,$dingnum,$time,$phone);
            //下单成功后，清空购物车
            $this->Gw->delgw($username);
            echo header("refresh:0;url='../person/dingdan'");
            echo "<script>alert('恭喜！下单成功！');</script>";

        }
    }
}