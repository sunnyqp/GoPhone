<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Phoneinfo as Hei;
use app\index\model\Category as Cg;
class Heikeji extends Controller
{
    protected $Hei;
    protected $Cg;
    public function _initialize()
    {
        $this->Hei = new Hei();
        $this->Cg = new Cg();
    }

    public function heikeji()
    {
        //机器人
        $jiqiren = $this->Hei->jiqiren();
        //无人机
        $wurenji = $this->Hei->wurenji();
        //手环
        $shouhuan = $this->Hei->shouhuan();
        //眼镜
        $yanjing = $this->Hei->yanjing();
        //手表
        $shoubiao = $this->Hei->shoubiao();
//        dump($yanjing);
		//分类
        $big = $this->Cg->big();
        $small = $this->Cg->small();

        if(!empty($big)){
            $this->assign('big',$big);
        }
        if(!empty($small)){
            $this->assign('small',$small);
        }

        $this->assign('jiqiren',$jiqiren);
        $this->assign('wurenji',$wurenji);
        $this->assign('yanjing',$yanjing);
        $this->assign('shouhuan',$shouhuan);
        $this->assign('shoubiao',$shoubiao);

        return $this->fetch();
    }
}