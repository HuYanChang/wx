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

    protected $table = 'wx_user';
    const BASEURL = 'https://api.weixin.qq.com';
    const APPID = 'wxe35be9a4ca8325f9';
    const ERRCODE = 200;
    private $APPSECRET = 'da5165a7fd883afd279b2b97e904c998';

    /**
     * @author hyc
     * @FunDesc:获取sessionKey
     * @param $code
     * @param $data
     * @return mixed
     */
    public function code2Session($code, &$data){
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
        }else{
            $data = $code2Session;
            return self::ERRCODE;
        }

    }

    /**
     * @FunDesc:获取用户的union_id
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $sessionKey string 请求的到的sessionKey
     * @param $encryptedData string 加密的数据
     * @param $iv string 解密的初向矢量
     * @param $data array 需要返回的数据
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData(string $sessionKey,string $encryptedData, string $iv,  array &$data)
    {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey=base64_decode($sessionKey);
        if (strlen($iv) != 24) {
            return -41003;
        }
        $aesIV=base64_decode($iv);
        $aesCipher=base64_decode($encryptedData);
        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataArr=json_decode($result, true);
        if( $dataArr  == NULL ){
            return -41004;
        }
        if( $dataArr['watermark']['appid'] != self::APPID ){
            return -41005;
        }
        $data = $dataArr;
        $this->_addUser($data);
        return 200;
    }

    //新增插入用户信息
    private function _addUser($data){
        $insertData = array(
            'nick_name' => $data['nickName'],
            'avatar' => $data['avatarUrl'],
            'openid' => $data['openid'],
            'language' => $data['language'],
            'country' => $data['country'],
            'province' => $data['province'],
            'city' => $data['city'],
            'gender' => $data['gender'],
            'union_id' => $data['unionId'],
            'create_time' => time()
        );
        parent::add($insertData);
    }
}