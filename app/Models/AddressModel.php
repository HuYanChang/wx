<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19
 * Time: 16:16
 */
namespace app\Models;

use app\Models\BaseInfoModel;
use wxphp\base\Model;

class AddressModel extends Model{
    protected $table = 'wx_address';
    protected $offset = 0;
    protected $limit = 10;
    const ERRCODE = 200;


    /**
     * @author hyc
     * @FunDesc:获取收货地址
     * @param $unionId
     * @param $offset
     * @param $limit
     * @param $data
     * @return int
     */
    public function addressList($unionId, $offset, $limit, &$data){
        $this->offset = $offset;
        $this->limit = $limit;
        $baseModel = new BaseInfoModel();
        $checkUser = $baseModel->checkUserExist($unionId);
        if(empty($checkUser)) return 5001;
        $col = ['receive_name', 'receive_address', 'receive_province', 'receive_city', 'receive_phone', 'postal_code'];
        parent::where($this->table, array('user_id = '.$checkUser['u_id']), [], $col);
        $addressList = parent::querySql('', 1);
        if(!empty($addressList)) $data = $addressList;
        return self::ERRCODE;
    }

}