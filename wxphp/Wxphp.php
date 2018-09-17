<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 10:20
 */

namespace wxphp;

//框架根目录
define('CORE_PATH', __DIR__);

/**
 * Class Wxphp
 * 框架核心
 * @package wxphp
 */
class Wxphp{
    //配置内容
    protected $config = [];
    public function __construct($config)
    {
        $this->config = $config;
    }

    //运行程序
    public function run()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->setReporting(); //检测app_debug
        $this->removeMagicQuotes(); //过滤非法字符
        $this->unregisterGlobals();//禁止注册全局变量
        $this->setDbConfig();  //数据库配置
        $this->route(); //路由分发
    }
    //路由处理
    public function route()
    {
        $controllerName = $this->config['defaultController'];
        $actionName = $this->config['defaultAction'];
        $param = array();

        //获取到url
        $url = $_SERVER['REQUEST_URI'];
        $position = strpos($url, '?');
        $url = $position === false? $url: substr($url, 0, $position);
        $url = trim($url, '/');

        /**
         * 若url存在
         * 获取控制器名
         * 获取方法名
         */
        if($url){
            $urlArray = explode('/', $url);
            $urlArray = array_filter($urlArray);
            $controllerName = ucfirst($urlArray[0]);
            array_shift($urlArray); //删除数组第一个并该值
            $actionName = $urlArray ? $urlArray[0]:$actionName;
            array_shift($urlArray);
            $param = $urlArray ? $urlArray : array();
        }
        //判断是否存在控制器和方法
        $controller = 'app\\Controllers\\'.$controllerName.'Controller';
        if(!class_exists($controller)){
            exit($controller."控制器不存在");
        }
        if(!method_exists($controller, $actionName)){
            exit($controller.'\\'.$actionName."方法不存在");
        }

        $dispatch = new $controller($controllerName, $actionName);
        // 也可以像方法中传入参数，以下等同于：$dispatch->$actionName($param)
        call_user_func_array(array($dispatch, $actionName), $param);
    }

    //检测开发环境
    public function setReporting()
    {
        if(APP_DEBUG === true){
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }else{
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
        }
    }

    // 删除敏感字符
    public function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }

    // 检测敏感字符并删除
    public function removeMagicQuotes()
    {
        if (get_magic_quotes_gpc()) {
            $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET ) : '';
            $_POST = isset($_POST) ? $this->stripSlashesDeep($_POST ) : '';
            $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
        }
    }

    public function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    // 配置数据库信息
    public function setDbConfig()
    {
        if ($this->config['db']) {
            define('DB_HOST', $this->config['db']['host']);
            define('DB_NAME', $this->config['db']['dbname']);
            define('DB_USER', $this->config['db']['username']);
            define('DB_PASS', $this->config['db']['password']);
        }
    }

    //自动加载类
    public function loadClass($className)
    {
        $classMap = $this->classMap();

        if(isset($classMap[$className])){
            //包含内核文件
            $file = str_replace('/', '\\', $classMap[$className]);
        }elseif(strpos($className, '\\') !== false){
            //包含app目录文件
            $file = APP_PATH . '\\' . $className. '.php';
            if(!is_file($file)){
                return ;
            }
        }else{
            return ;
        }
        include  $file;
    }

    // 内核文件命名空间映射关系
    protected function classMap()
    {
        return [
            'wxphp\base\Controller' => CORE_PATH . '/base/Controller.php',
            'wxphp\base\Model' => CORE_PATH . '/base/Model.php',
            'wxphp\base\View' => CORE_PATH . '/base/View.php',
            'wxphp\db\Db' => CORE_PATH . '/db/Db.php',
            'wxphp\db\Sql' => CORE_PATH . '/db/Sql.php',
        ];
    }
}