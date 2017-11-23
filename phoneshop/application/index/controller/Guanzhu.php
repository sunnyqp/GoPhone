<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Liulan as Ll;
use app\index\model\Guanzhu as Gz;
use think\Session;
use think\Model;
use app\index\model\Category as Cg;
class guanzhu extends Controller
{
    protected $Ll;
    protected $Gz;
    protected $Cg;

    public function _initialize()
    {
        if(empty(Session::get('username'))){
            echo header("refresh:0;url='../../'");
            exit("<script>alert('你这是什么操作！');</script>");
        }
        $this->Ll = new Ll();
        $this->Gz = new Gz();
        $this->Cg = new Cg();
    }
    //所有浏览记录
    public function liulanlishi()
    {
        if(!empty(Session::get('username'))){
            $username = Session::get('username');
            //查询该用户所有浏览历史
            $lishi = $this->Ll->lishi($username);
            $page = $lishi->render();

            $this->assign('lishi',$lishi);
            $this->assign('page', $page);

            return $this->fetch();
        }
    }
    //删除记录
    public function shanjilu()
    {
        if(!empty($_GET) && !empty(Session::get('username'))){
            $username = Session::get('username');
            $id = $_GET['id'];
            $this->Ll->shanjilu($id,$username);
            echo header("refresh:0;url='../guanzhu/liulanlishi'");
            echo "<script>alert('删除成功！');</script>";
        }
    }
    //用户所有关注的商品
    public function guanzhu()
    {
        if(!empty(Session::get('username'))){
            $username = Session::get('username');
            $guanzhu = $this->Gz->guanzhu($username);
            $page = $guanzhu->render();

            $this->assign('guanzhu',$guanzhu);
            $this->assign('page', $page);

            return $this->fetch();
        }
    }
    //添加关注
    public function addguanzhu()
    {
        if(!empty($_GET) && !empty(Session::get('username'))){
            $id = $_GET['id'];
            $username = Session::get('username');
            $this->Gz->addguanzhu($username,$id);
            echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$id'");
            echo "<script>alert('已添加到关注！');</script>";
        }
    }
    //商品详情里取消关注
    public function shanguanzhu()
    {
        if(!empty($_GET) && !empty(Session::get('username'))){
            $id = $_GET['id'];
            $username = Session::get('username');
            $this->Gz->shanguanzhu($username,$id);
            echo header("refresh:0;url='../phoneinfo/phoneinfo?id=$id");
            echo "<script>alert('已取消关注！');</script>";
        }
    }
    //个人信息里取消关注
    public function quxiaoguanzhu()
    {
        if(!empty($_GET) && !empty(Session::get('username'))){
            $id = $_GET['id'];
            $username = Session::get('username');
            $this->Gz->shanguanzhu($username,$id);
            echo header("refresh:0;url='../guanzhu/guanzhu");
            echo "<script>alert('已取消关注！');</script>";
        }
    }

}