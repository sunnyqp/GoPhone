<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Siteinfo extends Model
{
    public function info()
    {
        return $this->select();
    }
}