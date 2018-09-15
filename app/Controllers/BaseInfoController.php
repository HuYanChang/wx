<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 15:24
 */
namespace app\Controllers;
use app\Format\FormatData;

interface getBaseInfo{
    public function getSessionKey();
    public function getOpenId();
    public function getUnionId();
    const BASEURL = 'https://api.weixin.qq.com';
    const APPID = '';
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