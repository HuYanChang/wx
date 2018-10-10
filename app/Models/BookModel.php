<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 11:38
 */
namespace app\Model;
use wxphp\base\Model;
class BookModel extends Model{
    protected $table = 'wx_book';
    public function bookInfo($unionId){
        //是否存在该用户
        $checkUserInfo = $this->_checkUserExist($unionId);
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