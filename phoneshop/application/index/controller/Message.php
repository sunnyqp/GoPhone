<?php
namespace app\index\controller;
    use think\Controller;
    use app\index\model\Zixun as Zx;
    use app\index\model\Category as Cg;
    use api\News;
class Message extends Controller
{
    protected $Zx;
    protected $News;
    protected $Cg;
    public function _initialize()
    {
        $this->News = new News();
        $this->Cg = new Cg();
        $this->Zx = new Zx();
        $this->Cg = new Cg();
    }
    //资讯列表
    public function message()
    {
        //资讯
        $zixun = $this->Zx->zixun();

        $page = $zixun->render();

        //三条咨询
        $zi3xun = $this->Zx->zi3xun();
		//分类
        $big = $this->Cg->big();
        $small = $this->Cg->small();

        if(!empty($big)){
            $this->assign('big',$big);
        }
        if(!empty($small)){
            $this->assign('small',$small);
        }
		
        $this->assign('page', $page);
        $this->assign('zixun',$zixun);
        $this->assign('zi3xun',$zi3xun);
        //新闻
        //$this->news();
        return $this->fetch();
    }
    //资讯详情页
    public function zixun()
    {
        if(!empty($_GET)){
            $id = $_GET['id'];
            //点击量
            $click = $this->Zx->zixuninfo($id);
            $click = $click[0]->toArray()['click'];
            $click = $click + 1;
            $this->Zx->dianji($click,$id);

            $idup = $id-1;
            $iddown = $id +1;
            $zixuninfo = $this->Zx->zixuninfo($id);
            $zixunup = $this->Zx->zixunup($idup);
            $zixundown = $this->Zx->zixundown($iddown);

            //dump($zixundown);
            //三条咨询
            $zi3xun = $this->Zx->zi3xun();
            if(empty($zixuninfo)){
                echo header("refresh:0;url='message'");
                exit("<script>alert('非法操作！');</script>");
            }
            //dump($zixuninfo);
            $this->assign('zixuninfo',$zixuninfo);
            $this->assign('zi3xun',$zi3xun);
            $this->assign('zixunup',$zixunup);
            $this->assign('zixundown',$zixundown);
            //分类
            $big = $this->Cg->big();
            $small = $this->Cg->small();

            if(!empty($big)){
                $this->assign('big',$big);
            }
            if(!empty($small)){
                $this->assign('small',$small);
            }
            //dump($zixundown);
            return $this->fetch();
        }else{
            echo header("refresh:0;url='message'");
            exit("<script>alert('非法操作！');</script>");
        }
    }
    //新闻
    public function news()
    {
        $appkey = '3037c2bc6d981569';//你的appkey
        $channel='科技';//utf8 新闻频道(头条,财经,体育,娱乐,军事,教育,科技,NBA,股票,星座,女性,健康,育儿)
        $url = "http://api.jisuapi.com/news/get?channel=$channel&appkey=$appkey";

        $result = $this->News->curlOpen($url);

        $jsonarr = json_decode($result, true);
        if($jsonarr['status'] != 0)
        {
            echo $jsonarr['msg'];
            exit();
        }
        $result = $jsonarr['result'];

        $this->assign('news',$result['list']);

//      echo $val['title'].' '.$val['time'].' '.$val['src'].' '.$val['category'].' '.$val['pic'].' '.$val['content'].' '.$val['url'].' '.$val['weburl'] . '<br>';

    }
}