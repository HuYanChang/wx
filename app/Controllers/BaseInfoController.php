<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 15:24
 */
namespace app\Controllers;
use wxphp\base\Controller;
use lib\FormatDataTrait;
use app\Models\BaseInfoModel;

class BaseInfoController extends Controller{
    use FormatDataTrait;
    /**
     * @author hyc
     * @FunDesc:获取session_key
     */
    public function getCode2session()
    {
        $code = isset($_POST['code'])?$_POST['code']:'';
        $encryptedData = isset($_POST['encryptedData'])?$_POST['encryptedData']:'';
        $iv = isset($_POST['iv'])?$_POST['iv']:'';
        if(empty($code)) $this->responseDataFormat(10001);
        $baseInfoModel = new BaseInfoModel;
        $errcode = $baseInfoModel->code2Session($code, $encryptedData, $iv, $data);
        $this->responseDataFormat($errcode, $data);
    }
}