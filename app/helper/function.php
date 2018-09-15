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