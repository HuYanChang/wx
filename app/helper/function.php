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
    function httpGet($url, $dataType){
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