<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19
 * Time: 16:15
 */
namespace app\Controllers;

use lib\FormatDataTrait;
use wxphp\base\Controller;
use app\Models\AddressModel;

class AddressController extends Controller{
    use FormatDataTrait;
    protected $addressModel;
    public static $data = [];
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->addressModel = new AddressModel();
    }

    /**
     * @author hyc
     * @FunDesc:获取收货地址
     */
    public function addressList(){
        $unionId = isset($_GET['union_id'])? $_GET['union_id']: '';
        if(empty($unionId)) $this->responseDataFormat(10003);
        $page = isset($_GET['page'])? $_GET['page']: 1;
        $limit = isset($_GET['limit'])? $_GET['limit']: 10;
        $offset = ($page - 1) * $limit;
        $errcode = $this->addressModel->addressList($unionId, $offset, $limit, self::$data);
        if($errcode !== 200) $this->responseDataFormat($errcode);
        $this->responseDataFormat($errcode, self::$data);
    }
}