<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 18:35
 */
namespace wxphp\base;

use wxphp\db\Sql;

class Model extends Sql{
    protected $model;

    public function __construct()
    {
        //获取数据库表明
        if(!$this->table){
            //获取模型名称
            $this->model = get_class($this);

            //删除类名最后的model字符
            $this->model = substr($this->model, 0, -5);

            //数据库表名与类名一致
            $this->table = strtolower($this->model);
        }
    }
}