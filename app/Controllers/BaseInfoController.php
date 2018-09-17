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

class baseInfoController extends Controller{
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
        $code = $_POST('code');
        if(empty($code)) (new FormatData())->responseDataFormat(40035);
        $getOpenIdUrl = getBaseInfo::BASEURL.'/sns/jscode2session?';
        $data = array(
            'appid' => getBaseInfo::APPID,
            'secret' => getBaseInfo::APPSECRET,
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        );
        $getStr = arrayToStr($data);
        $getUrl = $getOpenIdUrl.$getStr;
        $code2Session = httpGet($getUrl);
        return $this->responseDataFormat(200);
        // TODO: Implement getSessionKey() method.
    }
}

$baseInfo = new baseInfoController;
$baseInfo->getCode2session();