<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
require_once(ROOT_PATH."\Spider\include\inc.php"););		//用户常量定义文件
require_once(ROOT_PATH."\Spider\class\db\db.inc.php");		//数据库和数据库表配置文件
require_once(ROOT_PATH.'Spider\class\log_class.php');		//包括日志类

?>