<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 11:38
 */
namespace app\Models;
use wxphp\base\Model;
class BookModel extends Model{
    protected $table = 'wx_book';
    protected $offset = 0;
    protected $limit = 1;
    const ERRCODE = 200;
    public function bookInfo($unionId, &$data, $offset, $limit){
        $this->offset = $offset;
        $this->limit = $limit;
        //是否存在该用户
        $checkUserInfo = $this->_checkUserExist($unionId);
        if(empty($checkUserInfo)) return 50001;
        //要查询的字段FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i') as create_time

        $col = ['order_id', 'total_money', 'purchase_price', 'goods_count', 'nick_name', 'FROM_UNIXTIME(create_time, "%Y-%m-%d %H:%i:%s") as create_time', 'shop_name'];
        parent::where('wx_book', array('user_id = '.$checkUserInfo['u_id']), [], $col);
        $bookList = parent::querySql('', 1);
        if(!empty($bookList)) $data = $bookList;
        return self::ERRCODE;
    }
    /**
     * @author hyc
     * @FunDesc:是否存在用户信息
     * @param $unionId string
     * @return mixed
     */
    private function _checkUserExist(string $unionId){
        parent::where('wx_user',array('union_id = "'.$unionId.'"'), [], ['u_id']);
        $existInfo = parent::fetch();
        return $existInfo;
    }

    public function bookDetail(int $orderId, &$data)
    {
        $column = array('goods_name', 'sku_name', 'price', 'coupon_price', 'coupon_name', 'create_time', 'pay_time', 'return_time');
        parent::where('wx_book_detail', array('order_id = '.$orderId), [], $column);
        $bookDetail = parent::fetch();
        if(!empty($bookDetail)) $data = $bookDetail;
        return self::ERRCODE;
    }
}