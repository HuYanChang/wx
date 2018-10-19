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
    const ERRCODE = 200;

    /**
     * @author hyc
     * @FunDesc:获取优惠券列表
     * @param $unionId
     * @param $offset
     * @param $limit
     * @param $isUse
     * @param $data
     * @return int
     */
    public function couponList($unionId, $offset, $limit, $isUse, &$data){
        $this->limit = $limit;
        $this->offset = $offset;
        $baseInfoModel = new BaseInfoModel();
        $checkUser = $baseInfoModel->checkUserExist($unionId);
        if(empty($checkUser)) return 50001;
        $col = ['coupon_name', 'coupon_expire', 'coupon_type', 'coupon_rule'];
        if($isUse){
            $where = array('user_id = '.$checkUser['user_id'], 'and ', 'status = 2');
        }else{
            $where = array('user_id = '.$checkUser['user_id']);
        }
        parent::where($this->table, $where,[], $col);
        $couponList = parent::querySql('', 1);
        if(!empty($couponList)) $data = $couponList;
        return self::ERRCODE;
    }
}