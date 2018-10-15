<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 18:39
 */
namespace wxphp\db;

use \PDOStatement;

class Sql{
    //数据库表名
    protected $table;

    protected $offset = 0;
    protected $limit = 1;

    //数据库主键
    protected $primary = 'id';

    //where 和order 拼装后的条件
    private $filter = '';

    //Pdo bingParam()绑定参数集合
    private $param = array();

    private $col = '';

    //查询条件
    public function where($table = '', $where = array(), $param = array(), $col = array())
    {
        if($where){
            $this->filter .= ' WHERE ';
            $this->filter .= implode(' ', $where);
            $this->param = $param;
        }
        if(!empty($col)){
            $this->col = implode(',', $col);
        }else{
            $this->col = '*';
        }
        if(!empty($table)){
            $this->table = $table;
        }
        return $this;
    }

    //排序查询
    public function order($order = array())
    {
        if($order){
            $this->filter .= ' ORDER BY ';
            $this->filter .= implode(',', $order);
        }
        return $this;
    }

    //查询所有
    public function fetchAll()
    {
       $sql = sprintf("select * from `%s` %s", $this->table, $this->filter);
       $sth = Db::pdo()->prepare($sql);
       $sth = $this->formatParam($sth, $this->param);
       $sth->execute();

       return $sth->fetchAll();
    }

    //查询一条
    public function fetch()
    {
        $sql = sprintf("select %s from `%s` %s limit %s, %s", $this->col, $this->table, $this->filter, $this->offset, $this->limit);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();

        return $sth->fetch();
    }

    //删除（id）数据
    public function delete($id)
    {
        $sql = sprintf("delete from `%s` where `%s` = :%s", $this->table, $this->primary, $this->primary);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$this->primary => $id]);

        return $sth->rowCount();
    }

    //新增数据
    public function add($data)
    {
        $sql = sprintf("insert into `%s` %s", $this->table, $this->formatInsert($data));
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);
        $sth->execute();

        return $sth->rowCount();
    }

    //修改数据
    public function update($data)
    {
        $sql = sprintf("update `%s` set %s %s", $this->table, $this->formatUpdate($data), $this->filter);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $data);
        $sth = $this->formatParam($sth, $this->param);

        return $sth->rowCount();
    }

    /**
     * 占位符绑定具体的变量值
     * @param PDOStatement $sth 要绑定的PDOStatement对象
     * @param array $params 参数，有三种类型：
     * 1）如果SQL语句用问号?占位符，那么$params应该为
     *    [$a, $b, $c]
     * 2）如果SQL语句用冒号:占位符，那么$params应该为
     *    ['a' => $a, 'b' => $b, 'c' => $c]
     *    或者
     *    [':a' => $a, ':b' => $b, ':c' => $c]
     *
     * @return PDOStatement
     */
    public function formatParam(PDOStatement $sth, $params = array())
    {
        foreach ($params as $param => &$value){
            $param = is_int($param)? $param + 1 : ':'. ltrim($param, ':');
            $sth->bindParam($param, $value);
        }

        return $sth;
    }

    //将数组换成更新格式sql
    private function formatInsert($data)
    {
        $fields = array();
        $names = array();
        foreach ($data as $key => $value){
            $fields[] = sprintf("`%s`", $key);
            $names[] = sprintf("%s", is_numeric($value)?$value:"'$value'");
        }
        $fields = implode(',', $fields);
        $names = implode(',', $names);
        return sprintf("(%s) values (%s)", $fields, $names);
    }

    //将数组转换成更新格式的sql语句
    private function formatUpdate($data)
    {
        $fields = array();
        foreach ($data as $key => $value){
            $fields[] = sprintf("`%s` = :%s", $key, is_numeric($value)? $value: "'$value'");
        }
        return implode(',', $fields);
    }
}