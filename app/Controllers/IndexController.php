<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 18:27
 */
namespace app\Controllers;

use wxphp\base\Controller;

class IndexController extends Controller{
    public function index(){
        echo 'I can see you !';
    }
}