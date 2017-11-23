<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Phoneinfo as Info;
use app\index\model\Zixun as Zx;
use app\index\model\Brand as Bd;
use app\index\model\Photo as Pt;
use app\index\model\User as Us;
use app\index\model\Address as Ad;
use app\index\model\Indent as In;
use app\index\model\Category as Cg;
use app\index\model\Siteinfo as Sf;
use think\Session;
use think\Model;
use api\Kuaidi;
use api\Xiaohua;
use api\Tizhong;
use api\Huoche;

class person extends Controller
{
    protected $Info;
    protected $Zx;
    protected $Bd;
    protected $Pt;
    protected $Us;
    protected $Ad;
    protected $In;
    protected $Kuaidi;
    protected $Xiaohua;
    protected $Tizhong;
    protected $Huoche;
    protected $Cg;
    protected $Sf;

    public function _initialize()
    {
        if(empty(Session::get('username'))){
            echo header("refresh:0;url='../../'");
            exit("<script>alert('你这是什么操作！');</script>");
        }
        $this->Info = new Info();
        $this->Zx = new Zx();
        $this->Bd = new Bd();
        $this->Pt = new Pt();
        $this->Us = new Us();
        $this->Ad = new Ad();
        $this->In = new In();
        $this->Cg = new Cg();
        $this->Sf = new Sf();
        $this->Kuaidi = new Kuaidi();
        $this->Xiaohua = new Xiaohua();
        $this->Tizhong = new Tizhong();
        $this->Huoche = new Huoche();
    }
    //个人信息
    public function person()
    {
        return $this->fetch();
    }

    //遍历用户信息
    public function personinfo()
    {
        //体重
        if(!empty($_POST['zhong']) && !empty($_POST['gao'])){
            $zhong = $_POST['zhong'];
            $gao = $_POST['gao'];

            $appkey = '3037c2bc6d981569';//你的appkey
            $sex = 'male';//male famale
            $height = $gao;//cm
            $weight = $zhong;//kg
            $url = "http://api.jisuapi.com/weight/bmi?appkey=$appkey&sex=$sex&height=$height&weight=$weight";

            $result = $this->Tizhong->curlOpen($url);

            $jsonarr = json_decode($result, true);

            //exit(var_dump($jsonarr));

            if($jsonarr['status'] != 0)
            {
                echo $jsonarr['msg'];
                exit();
            }

            if(!empty($result)){
                $result = $jsonarr['result'];
            }
            $this->assign('tizhong',$result);
        }
        if(!empty(Session::get('username'))){
            $username = Session::get('username');
            $info = $this->Us->info($username)[0]->toArray();

            //dump($info);
            $realname = $info['realname'];
            $username = $info['username'];
            $nickname = $info['nickname'];
            $userpic = $info['userpic'];
            $email = $info['email'];
            $sex = $info['sex'];
            $phone = $info['phone'];

            $this->assign('realname',$realname);
            $this->assign('username',$username);
            $this->assign('nickname',$nickname);
            $this->assign('userpic',$userpic);
            $this->assign('email',$email);
            $this->assign('sex',$sex);
            $this->assign('phone',$phone);
            return $this->fetch();
        }

    }
    //修改数据
        public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');
            if(empty($file)){
                if(!empty($_POST)){
                    $nickName = $_POST['nickName'];
                    $zsName = $_POST['zsName'];
                    $sex = $_POST['sex'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    //插入
                    $this->Us->save(
                        [
                            'nickname' => "$nickName",
                            'realname' => "$zsName",
                            'sex' => $sex,
                            'email' => "$email",
                            'phone' => "$phone",
                        ],
                        ['username' => Session::get('username')]
                    );
                    //echo header("refresh:0;url='../person/personinfo'");
                }
                echo header("refresh:0;url='../person/personinfo'");
                echo "<script>alert('Ok！');</script>";
            }else{
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            }
    // 移动到框架应用根目录/public/uploads/ 目录下
            if(!empty($info)){
    // 成功上传后 获取上传信息
    // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    $userpic = "../../../../uploads/". $info->getSaveName();
                    $userpic = str_replace('\\','/',$userpic);
                if(!empty($_POST)){
                    $nickName = $_POST['nickName'];
                    $zsName = $_POST['zsName'];
                    $sex = $_POST['sex'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    //插入
                    $this->Us->save(
                        [
                            'nickname' => "$nickName",
                            'realname' => "$zsName",
                            'sex' => $sex,
                            'email' => "$email",
                            'phone' => "$phone",
                            'userpic' => "$userpic"
                            ],
                        ['username' => Session::get('username')]
                    );
                    echo header("refresh:0;url='../person/personinfo'");
                    echo "<script>alert('Ok！');</script>";
                }
            }else{
    // 上传失败获取错误信息
                echo header("refresh:0;url='../person/personinfo'");
                //exit("<script>alert('失败了！换个图试试！');</script>");
            }
        }

        //修改密码页面
        public function gaimima()
        {
            return $this->fetch();
        }
        //修改密码
        public function gaimi()
        {
            $username = Session::get('username');
            $pass = $this->Us->info($username)[0]->toArray()['password'];
            $old = md5($this->request->param('oldpwd'));
            $new = md5($this->request->param('newpwd'));
            if($old==$pass){
                echo 1;
                $this->Us->gaimi($new,$username);
                echo header("refresh:0;url='../person/gaimi'");
            }
        }
        //收获地址
        public function address()
        {
            $username = Session::get('username');
            //用户所有的收货地址
            $address = $this->Ad->addinfo($username);

            $this->assign('address',$address);
            return $this->fetch();
        }
        //删除收货地址
        public function deladdress()
        {
            if(!empty($_GET)){
                $id = $_GET['id'];
                $this->Ad->del($id);
                echo header("refresh:0;url='../person/address'");
            }
        }
        //添加收货地址
        public function tian()
        {
            $username = Session::get('username');
            if(!empty($_POST['shou'])|| !empty($_POST['address']) || !empty($_POST['phone'])){
                //dump($_POST);
                $shou = $_POST['shou'];
                $province = $_POST['province'];
                if(!empty($_POST['city'])){
                    $city = $_POST['city'];
                }else{
                    $city = null;
                }
                $area = $_POST['area'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];
                if(preg_match("/^1[34578]{1}\d{9}$/",$phone)==0){
                    echo header("refresh:0;url='../person/address'");
                    exit("<script>alert('手机号很重要,请正确输入!');</script>");
                }
                //添加收货地址
                $this->Ad->tian($username,$shou,$province,$city,$area,$address,$phone);
                echo "<script>alert('添加成功!');</script>";
                echo header("refresh:0;url='../person/address'");
            }else{
                echo header("refresh:0;url='../person/address'");
                exit("<script>alert('请输入完整信息!');</script>");
            }
        }
        //修改是否默认
        public function ismoren()
        {
            if(!empty($_GET)){
                $id = $_GET['id'];
                $is = $_GET['is'];
                $rel = $this->Ad->ismoren($id,$is);
                echo header("refresh:0;url='../person/bumoren?id=$id'");
            }

        }
        //设置默认后把其他所有的都不默认
        public function bumoren()
        {
            if(!empty($_GET)){
                $id = $_GET['id'];
                $this->Ad->bumoren($id);
                echo header("refresh:0;url='../person/address'");
            }
        }
        //修改页面
        public function gaidizhi()
        {
            if(!empty($_GET)){
                $id = $_GET['id'];
                $rel = $this->Ad->gaidizhi($id)[0]->toArray();

                $id = $rel['id'];
                $province = $rel['province'];
                $city = $rel['city'];
                $area = $rel['area'];
                $address = $rel['address'];
                $shou = $rel['shou'];
                $phone = $rel['phone'];

                $this->assign('id',$id);
                $this->assign('province',$province);
                $this->assign('city',$city);
                $this->assign('area',$area);
                $this->assign('address',$address);
                $this->assign('shou',$shou);
                $this->assign('phone',$phone);

                return $this->fetch();
            }
        }
        //修改地址
        public function xiugaidizhi()
        {
            if(!empty($_GET)){
                $id = $_GET['id'];
            if(!empty($_POST['shou'])|| !empty($_POST['address']) || !empty($_POST['phone'])){
                //dump($_POST);
                $shou = $_POST['shou'];
                $province = $_POST['province'];
                if(!empty($_POST['city'])){
                    $city = $_POST['city'];
                }else{
                    $city = null;
                }
                $area = $_POST['area'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];
                if(preg_match("/^1[34578]{1}\d{9}$/",$phone)==0){
                    echo header("refresh:0;url='../person/gaidizhi?id=$id'");
                    exit("<script>alert('手机号很重要,请正确输入!');</script>");
                }
                if(empty($address) || empty($shou) || empty($phone)){
                    echo header("refresh:0;url='../person/gaidizhi?id=$id'");
                    exit("<script>alert('请输入完整信息!');</script>");
                }
                //修改收货地址
                $this->Ad->xiugaidizhi($id,$shou,$province,$city,$area,$address,$phone);
                echo "<script>alert('修改成功!');</script>";
                echo header("refresh:0;url='../person/address'");
                }
            }else{
                echo header("refresh:0;url='../person/address'");
                exit("<script>alert('你这是什么操作!');</script>");
            }
        }
        //订单中心
        public function dingdan()
        {
            $username = Session::get('username');
            $dingdan = $this->In->dingdan($username);
            //$allname = $this->In->dingdan1($username);
            //dump($allname);
//            $alln = [];
//            foreach ($allname as $v){
//                $alln[] = $v['gid'];
//            }
//            dump($alln);
            $page = $dingdan->render();
            $this->assign('page',$page);
//            $this->assign('alln',$alln);
            $this->assign('dingdan',$dingdan);
            return $this->fetch();
        }
        //物流信息页面
        public function kuaidi()
        {
            if (!empty($_POST['kuaidi'])) {
                $kuaidi = $_POST['kuaidi'];

                $appkey = '3037c2bc6d981569';//你的appkey
                $url = "http://api.jisuapi.com/express/query?appkey=$appkey";
                $type = 'auto';
                $number = $kuaidi;

                $post = array('type' => $type,
                    'number' => $number
                );

                $result = $this->Kuaidi->curlOpen($url, array('post' => $post));
                //$result = curlOpen($url, array('post' => $post));

                $jsonarr = json_decode($result, true);
                //exit(var_dump($jsonarr));

                if ($jsonarr['status'] != 0) {
                    echo $jsonarr['msg'];
                    exit();
                }

                //状态（１：签收　其他：未签收）
                $result = $jsonarr['result'];
                $kuaidi = $result['list'];
                if(!empty($kuaidi)){
                    $this->assign('kuaidi',$kuaidi);
                    $this->assign('result',$result);
                }
            }
            return $this->fetch();
        }
        //火车查询
        public function huoche()
        {
            if (!empty($_POST['chufa']) && !empty($_POST['daoda'])) {
                $chufa = $_POST['chufa'];
                $daoda = $_POST['daoda'];
                $isgaotie = $_POST['isgaotie'];
                //dump($_POST);
                $appkey = '3037c2bc6d981569';//你的appkey
                $start = "$chufa";//utf8
                $end = "$daoda";//utf8
                $ishigh = $isgaotie;
                $url = "http://api.jisuapi.com/train/station2s?appkey=$appkey&start=$start&end=$end&ishigh=$ishigh";

                $result = $this->Huoche->curlOpen($url);

                $jsonarr = json_decode($result, true);
                //exit(var_dump($jsonarr));
                if ($jsonarr['status'] != 0) {
                    echo $jsonarr['msg'];
                    exit();
                }
                if(!empty($jsonarr['result'])){
                    $this->assign('huoche',$jsonarr['result']);
                }
            }
            return $this->fetch();
        }
}