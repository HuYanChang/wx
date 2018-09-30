<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:50
 */

namespace app\Models;

class EncryptModel{
    /**
     * @author hyc
     * @FunDesc:解密获取sessionKey
     * @param $encryptStr
     * @return int
     */
    public function DecryptSessionKey($encryptStr){
        $decryptStr = base64_decode($encryptStr);
        if(strlen($decryptStr) < 16) return 10002;
        $decryptArr = explode('@', $decryptStr);
        $sessionKey = $decryptArr[2];
        return $sessionKey;
    }

    /**
     * @author hyc
     * @FunDesc:加密openid和sessionKey
     * @param $openId
     * @param $sessionKey
     * @return string
     */
    public function encryptSessionKey($openId, $sessionKey){
        $readyStr = $openId.'@'.date('YmdHis', time()).'@'.$sessionKey;
        $encryptStr = base64_encode($readyStr);
        return $encryptStr;
    }
}