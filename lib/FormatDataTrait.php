<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15
 * Time: 15:34
 */
namespace lib;

Trait FormatDataTrait{
    public static function responseDataFormat(int $errCode = 200, array $data = [],string $errMsg = 'SUCCESS',bool $isJson = true){
        if($errCode !==200){
            $data = array(
                'code' => $errCode,
                'code_msg' => config('error.'.$errCode)
            );
        }else{
            $data = array(
                'code' => $errCode,
                'code_msg' => $errMsg,
                'list' => $data
            );
        }
        if($isJson){
           $res =  self::dataToJson($data);
        }else{
           $res =  self::dataToXml($data);
        }
        echo $res;exit;
    }
    public static function dataToJson(array $data){
        return json_encode($data);
    }
    public static function dataToXml(array $data){
        header('Content-Type:text/xml');
        $attr = $xml = "";
        $xml .= '<?xml version="1.0" encoding="utf-8" ?>';
        $xml .= "<xml>";
        foreach ($data as $key => $value){
            if(is_numeric($key)){
                $attr = " id='{$key}'";
                $key = 'item';
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value)?self::dataToXml($value):$value;
            $xml .= "</{$key}>";
        }
        $xml .= "</xml>";
        return $xml;
    }
}