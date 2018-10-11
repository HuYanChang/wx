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
    public function bookInfo($unionId, &$data, $offset, $limit){
        $this->offset = $offset;
        $this->limit = $limit;
        //是否存在该用户
        $checkUserInfo = $this->_checkUserExist($unionId);
        if(empty($checkUserInfo)) return 50001;
        parent::where(array('user_id = '.$checkUserInfo['user_id']), [], ['total_money', 'purchase_price', 'goods_count']);
        $data = parent::fetch();
    }
    /**
     * @author hyc
     * @FunDesc:是否存在用户信息
     * @param $unionId string
     * @return mixed
     */
    private function _checkUserExist(string $unionId){
        parent::where(array('unionId = "'.$unionId.'"'), [], ['user_id']);
        $existInfo = parent::fetch();
        return $existInfo;
    }

}