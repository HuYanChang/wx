<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 15:24
 */
namespace app\Models;
use wxphp\base\Model;

class BaseInfoModel extends Model {
    const BASEURL = 'https://api.weixin.qq.com';
    const APPID = 'wxe35be9a4ca8325f9';
    const ERRCODE = 200;
    private $APPSECRET = 'da5165a7fd883afd279b2b97e904c998';
    private $encryptedData = ''; //加密数据
    private $iv = ''; //加密算法的初始向量

    /**
     * @author hyc
     * @FunDesc:获取sessionKey
     * @param $code
     * @param $encryptedData
     * @param $iv
     * @param $data
     * @return mixed
     */
    public function code2Session($code, $encryptedData, $iv, &$data){
        $getOpenIdUrl = self::BASEURL.'/sns/jscode2session?';
        $getData = array(
            'appid' => self::APPID,
            'secret' => $this->APPSECRET,
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        );
        $getStr = arrayToStr($getData);
        $getUrl = $getOpenIdUrl.$getStr;
        $code2Session = httpGet($getUrl);
        $code2Session = json_decode($code2Session, true);
        if(isset($code2Session['errcode']) && $code2Session['errcode'] !==0){
            return $code2Session['errcode'];
        }elseif (isset($code2Session['unionid'])){
            $data = $code2Session;
            return self::ERRCODE;
        }else{
            if(!empty($encryptedData) && !empty($iv)){
                $this->encryptedData = $encryptedData;
                $this->iv = $iv;
                $errcode = $this->_decryptData($code2Session['session_key'], $code2Session);
                return $errcode;
            }else{
                $data = $code2Session;
                return self::ERRCODE;
            }
        }

    }

    /**
     * @FunDesc:获取用户的union_id
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $sessionKey string 请求的到的sessionKey
     * @param $data
     * @return int 成功0，失败返回对应的错误码
     */
    private function _decryptData(string $sessionKey, &$data)
    {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey=base64_decode($sessionKey);


        if (strlen($this->iv) != 24) {
            return -41003;
        }
        $aesIV=base64_decode($this->iv);

        $aesCipher=base64_decode($this->encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataArr=json_decode($result, true);
        if( $dataArr  == NULL )
        {
            return -41004;
        }
        if( $dataArr['watermark']['appid'] != self::APPID )
        {
            return -41005;
        }
        $data = $dataArr;
        return 200;
    }
}