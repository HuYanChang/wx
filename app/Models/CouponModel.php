<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19
 * Time: 14:15
 */
namespace app\Models;

use wxphp\base\Model;
use app\Models\BaseInfoModel;
class CouponModel extends Model{

    protected $table = 'wx_coupon';
    protected $limit = 10;
    protected $offset = 0;

    public function couponList($unionId, $offset, $limit, &$data){
        $this->limit = $limit;
        $this->offset = $offset;
        $baseInfoModel = new BaseInfoModel();
        $checkUser = $baseInfoModel->checkUserExist($unionId);
        if(empty($checkUser)) return 50001;

    }
}