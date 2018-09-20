<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 17:38
 */

if(!function_exists('arrayToStr')){
    function arrayToStr($arrData)
    {
        $queryStr = '';
        foreach ($arrData as $key => $val) {
            if ((string)$val === '') continue;
            $queryStr .= $key . '=' . $val . '&';
        }
        $queryStr = trim($queryStr, '&');
        return $queryStr;
    }
}

if(!function_exists('httpGet')){
    function httpGet($url, $dataType = false){
        $oCurl = curl_init();
        if(stripos($url, "https://")!==FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
        }
        if($dataType) {
            $header[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($oCurl,CURLOPT_HTTPHEADER,$header);
        } else {
            $header[] = 'Content-Type:application/json;charset=utf-8';
            curl_setopt($oCurl,CURLOPT_HTTPHEADER,$header);
        }

        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200) {
            return $sContent;
        } else {
            return false;
        }
    }
}

if(!function_exists('httpPost')){
    function httpPost($url, $param, $dataType=false, $postFile=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
        }
        if (is_string($param) || $postFile) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }

        if($dataType == 2) {
            $header[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($oCurl,CURLOPT_HTTPHEADER,$header);
        } else {
            if($dataType) {
                $header[] = 'Content-Type:application/json;charset=utf-8';
                curl_setopt($oCurl,CURLOPT_HTTPHEADER,$header);
            }
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        if($postFile) curl_setopt($oCurl, CURLOPT_NOBODY, true);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);

        if(intval($aStatus["http_code"])==200) {
            return $sContent;
        } else {
            return false;
        }
    }
}

if (!function_exists('config')) {
    /**
     * 用于获取某个配置文件中的配置值
     * 默认获取config文件中的配置值
     * @param $value string/array 需要获取的配置项的名字 route.controller ['route','controller']
     * @return mixed|null
     */
    function config($value)
    {
        $file = 'config';
        $name = '';
        if (empty($value)) {
            return null;
        } elseif (is_array($value)) {
            $file = trim($value[0]);
            $name = trim($value[1]);
        } elseif (is_string($value)) {
            $param = explode('.', $value);
            if (count($param) > 1) {
                $file = trim($param[0]);
                $name = trim($param[1]);
            } else {
                $name = trim($value);
            }
        }

            $file_path = APP_PATH . '\config\\'  . $file . '.php';
            if (is_file($file_path)) {
                $config = include $file_path;
                if (isset($config[$name])) {
                    return $config[$name];
                } else {
                    throw new \Exception('找不到该配置项：' . $name);
                }
            } else {
                throw new \Exception('找不到该配置文件：' . $file . '.php' . '路径：' . $file_path);
            }
        }
}