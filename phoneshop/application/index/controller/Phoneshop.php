<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Brand as Pinpai;
use app\index\model\Phoneinfo as Info;
use app\index\model\Category as Cg;
class Phoneshop extends Controller
{
    protected $Pinpai;
    protected $Info;
    protected $Cg;
    public function _initialize()
    {
        $this->Pinpai = new Pinpai();
        $this->Info = new Info();
        $this->Cg = new Cg();
    }
    public function phoneshop()
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
        //所有品牌
        $pinpai = $this->Pinpai->brand();
        //各个品牌手机的详细信息
        $pingguo = $this->Info->pingguo();
        $huawei = $this->Info->huawei();
        $xiaomi = $this->Info->xiaomi();
        $oppo = $this->Info->oppo();
        $meizu = $this->Info->meizu();
        $vivo = $this->Info->vivo();
        $sanxing = $this->Info->sanxing();
		
        $this->assign('pinpai',$pinpai);

        $this->assign('pingguo',$pingguo);
        $this->assign('huawei',$huawei);
        $this->assign('xiaomi',$xiaomi);
        $this->assign('oppo',$oppo);
        $this->assign('meizu',$meizu);
        $this->assign('vivo',$vivo);
        $this->assign('sanxing',$sanxing);



        return $this->fetch();
    }
}