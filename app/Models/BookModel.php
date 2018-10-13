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
        $col = ['total_money', 'purchase_price', 'goods_count', 'buyer', 'create_time'];
        parent::where(array('user_id = '.$checkUserInfo['u_id']), [], $col);
        $data = parent::fetch();
        return self::ERRCODE;
    }
    /**
     * @author hyc
     * @FunDesc:是否存在用户信息
     * @param $unionId string
     * @return mixed
     */
    private function _checkUserExist(string $unionId){
        $this->table = 'wx_user';
        parent::where(array('union_id = "'.$unionId.'"'), [], ['u_id']);
        $existInfo = parent::fetch();
        return $existInfo;
    }

}