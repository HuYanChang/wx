<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 18:27
 */

namespace wxphp\base;

/**
 * 基类
 */

class Controller{
    protected $_controller;
    protected $_action;
    protected $_view;

    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
    }

    //分配变量
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }

    //渲染视图
    public function render()
    {
        $this->_view->render();
    }
}