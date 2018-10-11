<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/11
 * Time: 17:07
 */
namespace app\Controllers;

use wxphp\base\Controller;
use lib\FormatDataTrait;
use app\Models\BookModel;

class BookController extends Controller{
    use FormatDataTrait;
    public static $data = [];
    protected $bookModel;
    function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->bookModel = new BookModel;
    }

    public function bookList(){
        $unionId = isset($_GET['union_id'])??'';
        if(empty($unionId)) $this->responseDataFormat(10001);
        $code = $this->bookModel->bookInfo($unionId, self::$data);
        if($code !== 200) $this->responseDataFormat($code);
        $this->responseDataFormat($code, self::$data);
    }
}