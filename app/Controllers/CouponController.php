<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19
 * Time: 14:15
 */
namespace app\Controllers;

use wxphp\base\Controller;
use lib\FormatDataTrait;
use app\Models\CouponModel;
class CouponController extends Controller{
    use FormatDataTrait;
    protected static $data = [];
    protected $couponModel;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->couponModel = new CouponModel();

    }
    public function couponList(){
        $unionId = isset($_GET['union_id'])?$_GET['union_id']: '';
        if(empty($unionId)) $this->responseDataFormat(10003);
        $page = isset($_GET['page'])? $_GET['page']: 1;
        $limit = isset($_GET['limit'])? $_GET['limit']: 10;
        $offset = ($page - 1)*$limit;
        $errcode = $this->couponModel->couponList($unionId, $offset, $limit, self::$data);
    }
}