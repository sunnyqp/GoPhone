<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Phoneinfo as Sousuo;
use app\index\model\Category as Cg;

class Sou extends Controller
{
    protected $Sou;
    protected $Cg;
    public function _initialize()
    {
        $this->Sou = new Sousuo();
        $this->Cg = new Cg();
    }
    public function sou()
    {
		//分类
        $big = $this->Cg->big();
        $small = $this->Cg->small();

        if(!empty($big)){
            $this->assign('big',$big);
        }
        if(!empty($small)){
            $this->assign('small',$small);
        }

        if(!empty($_GET) || !empty($_POST)){
            if(!empty($_GET)){
                if(!empty($_GET['id'])){
                    $id = $_GET['id'];
                    $sou = $this->Sou->pid($id);
                    //统计搜索到的数量
                    $count = count($sou);
                    $this->assign('sou',$sou);
                    $this->assign('count',$count);
                }
                if(!empty($_GET['name'])){
                    $name = $_GET['name'];
                    $sou = $this->Sou->name($name);
                    //统计搜索到的数量
                    $count = count($sou);
                    $this->assign('sou',$sou);
                    $this->assign('count',$count);
                }

                return $this->fetch();
            }
            if(!empty($_POST)){
                $so = trim($_POST['so']);
                if($so==''){
                    echo header("refresh:0;url='../../'");
                    exit("<script>alert('请输入搜索内容！');</script>");
                }
                //搜索到的内容
                $sou = $this->Sou->sou($so);
                //统计搜索到的数量
                $count = count($sou);
                //dump($count);
                //导航栏搜索
                $this->fenlei();
                $this->assign('sou',$sou);
                $this->assign('so',$so);
                $this->assign('count',$count);
                return $this->fetch();
            }
        }else{
            echo header("refresh:0;url='../../'");
            exit("<script>alert('非法操作！');</script>");
        }
    }
    //分类的连接
    public function fenlei()
    {
        if(!empty($_GET)){
            $name = $_GET['name'];
            $fenlei = $this->Sou->fenlei($name);
            if(!empty($fenlei)){
                $this->assign('fenlei',$fenlei);
            }
        }
    }
}