<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 15:24
 */
namespace app\Controllers;
use app\Format\FormatData;
include './../helper/function.php';

interface getBaseInfo{
    public function getSessionKey();
    public function getOpenId();
    public function getUnionId();
    const BASEURL = 'https://api.weixin.qq.com';
    const APPID = 'wxe35be9a4ca8325f9';
    const APPSECRET = 'da5165a7fd883afd279b2b97e904c998';
}

class baseInfoController implements getBaseInfo{
    /**
     * @author hyc
     * @FunDesc:获取session_key
     */
    public function getSessionKey()
    {
        $code = $_GET('code');
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
        // TODO: Implement getSessionKey() method.
    }

    /**
     * @author hyc
     * @FunDesc:获取用户openid
     */
    public function getOpenId()
    {
        // TODO: Implement getOpenId() method.
    }

    /**
     * @author hyc
     * @FunDesc:获取union_id
     */
    public function getUnionId()
    {
        // TODO: Implement getUnionId() method.
    }
}