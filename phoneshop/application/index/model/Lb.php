<?php
    namespace app\index\model;
    use think\Model;

    class Lb extends Model
    {
        public function lb()
        {
            //查询所有轮播图
            return $this->select();
        }
    }