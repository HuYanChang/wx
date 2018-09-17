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

class BaseInfoController extends Controller{
    use FormatDataTrait;
    const BASEURL = 'https://api.weixin.qq.com';
    const APPID = 'wxe35be9a4ca8325f9';
    const APPSECRET = 'da5165a7fd883afd279b2b97e904c998';
    /**
     * @author hyc
     * @FunDesc:获取session_key
     */
    public function getCode2session()
    {
        $code = isset($_POST['code'])??'';
        if(empty($code)) echo $this->responseDataFormat(10001); exit;
        $getOpenIdUrl = self::BASEURL.'/sns/jscode2session?';
        $data = array(
            'appid' => self::APPID,
            'secret' => self::APPSECRET,
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        );
        $getStr = arrayToStr($data);
        $getUrl = $getOpenIdUrl.$getStr;
        $code2Session = httpGet($getUrl);
        if($code2Session['errcode'] !==0){
            $errcode = $code2Session['errcode'];
            $data = [];
            var_dump($code2Session);
        }else{
            $errcode = 200;
            $data = $code2Session;
        }
        echo $this->responseDataFormat($errcode, $data);
    }
}