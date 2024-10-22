<?php
define('BASE_PATH', getcwd());
//这个解决wamp环境下面验证码错误的BUG,如果后台验证码错误，用此办法解决
session_start();
$lifeTime = 30000; 
setcookie(session_name(), session_id(), time() + $lifeTime, "/");

//安全用的
if (ini_get('magic_quotes_gpc')) {
    function stripslashesRecursive(array $array) {
        foreach ($array as $k => $v) {
            if (is_string($v)) {
                $array[$k] = stripslashes($v);
            } else if (is_array($v)) {
                $array[$k] = stripslashesRecursive($v);
            }
        }
        return $array;
    }
    $_GET = stripslashesRecursive($_GET);
    $_POST = stripslashesRecursive($_POST);
}
//如果检测不到安装锁定文件，提示安装。
if (!file_exists(BASE_PATH . '/attachs/install.lock')) {
    header("Location: install/index.php");
    die;
}

//调试模式,暂时开启，正式运营的时候建议关闭
//ini_set('display_errors','On');
//error_reporting(0);
ini_set('display_errors','On');
error_reporting(0);
define('APP_DEBUG', true);




		
		

//定义项目名称，要修改版权的要重新定义这个名字
define('APP_NAME', 'Tudou');
ini_set('date.timezone', 'Asia/Shanghai');
define('TODAY', date('Y-m-d', $_SERVER['REQUEST_TIME']));

//定义项目路径，要修改版权的可以修改这里，以及项目分组里面的Tudou修改为你需要修改的文字。
define('APP_PATH', BASE_PATH . '/Tudou/');


//加载框架入文件，如果要修改目录名字，请不要忘记在这里修改下路径。
require './Core/ThinkPHP.php';