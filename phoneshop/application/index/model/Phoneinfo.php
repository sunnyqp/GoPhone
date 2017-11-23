<?php
    namespace app\index\model;
    use think\Model;

    class Phoneinfo extends Model
    {
        //查询所有手机信息
        public function allinfo()
        {
            return $this->select();
        }
        //查询热卖的手机信息（只取五部）
        public function hotinfo()
        {
            return $this->where('ishot',1)->where('pid','<>',8)->where('pid','<>','9')->limit(5)->select();
        }
        //查询热卖的手机信息
        public function allhotinfo()
        {
            return $this->where('ishot',1)->where('pid','<>',8)->where('pid','<>','9')->select();
        }
        //查询所有热卖的产品（取5部）
        public function hot5info()
        {
            return $this->where('ishot',1)->limit(5)->select();
        }
        //查询所有热卖的产品（取3部）
        public function hot3info()
        {
            return $this->where('ishot',1)->limit(3)->select();
        }
        //查询所有最新机型按照时间排序
        public function newinfo()
        {
            return $this->where('pid','<>',8)->where('pid','<>','9')->order('addtime','desc')->limit(8)->select();
        }
        //查询所有黑科技
        public function heiinfo()
        {
            return $this->where('pid',8)->select();
        }
        //查询黑科技(机器人)
        public function jiqiren()
        {
            return $this->where('pid',8)->whereLike('name','%机器人%')->limit(3)->select();
        }
        //查询黑科技(无人机)
        public function wurenji()
        {
            return $this->where('pid',8)->whereLike('name','%无人机%')->limit(3)->select();
        }
        //查询黑科技(手环)
        public function shouhuan()
        {
            return $this->where('pid',8)->whereLike('name','%手环%')->limit(3)->select();
        }
        //查询黑科技(眼镜)
        public function yanjing()
        {
            return $this->where('pid',8)->whereLike('name','%眼镜%')->limit(3)->select();
        }
        //查询黑科技(手表)
        public function shoubiao()
        {
            return $this->where('pid',8)->whereLike('name','%手表%')->limit(3)->select();
        }
        //查询黑科技(取六部)
        public function hei6info()
        {
            return $this->where('pid',8)->limit(6)->select();
        }
        //查询所有平板
        public function pinginfo()
        {
            return $this->where('pid',9)->select();
        }
        //查询平板（取六部）
        public function ping6info()
        {
            return $this->where('pid',9)->limit(6)->select();
        }

        //查询所有苹果手机
        public function pingguo()
        {
            return $this->where('pid',1)->select();
        }
        //查询所有华为手机
        public function huawei()
        {
            return $this->where('pid',2)->select();
        }
        //查询所有小米手机
        public function xiaomi()
        {
            return $this->where('pid',3)->select();
        }
        //查询所有oppo手机
        public function oppo()
        {
            return $this->where('pid',4)->select();
        }
        //查询所有魅族手机
        public function meizu()
        {
            return $this->where('pid',5)->select();
        }
        //查询所有vivo手机
        public function vivo()
        {
            return $this->where('pid',6)->select();
        }
        //查询所有三星手机
        public function sanxing()
        {
            return $this->where('pid',7)->select();
        }
        //搜索
        public function sou($so)
        {
            return $this->whereLike('name',"%$so%")->select();
        }
        //搜索等于对应id=pid的内容
        public function pid($id)
        {
            return $this->where('pid',$id)->select();
        }
        //搜索等于对应id=id的内容
        public function pids($id)
        {
            return $this->where('gid',$id)->select();
        }
        //搜索id的所有产品(取5部)
        public function tongpai($tongid)
        {
            return $this->where('pid',$tongid)->limit(5)->select();
        }
        //查询产品gid的所有信息
        public function gidinfo($gid)
        {
            return $this->whereIn('gid',$gid)->select();
        }
        //查询含有某内容的信息
        public function name($name)
        {
            return $this->whereLike('name',"%$name%")->select();
        }
        //库存
        public function kucun($gid)
        {
            return $this->where('gid',$gid)->select();
        }
        //减掉卖的产品(更新库存)
        public function jian($kucun,$gid)
        {
            return $this->where('gid',$gid)->update(['stock'=>$kucun]);
        }

    }