<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
require_once(ROOT_PATH.'\\Spider\\class\\log_class.php');		//包括日志类
/**
 * constant define.
 */
class DEFAULT_DATABASE_INFO_CONSTANTS{
	public static $db_info = array(
	SERVERNAME => "localhost",
	USERNAME => "root",
	PASSWD => "",
	DBNAME => "cenker"
	);
}

class cDatabase{
	protected $con;
	protected $cLog; 
	function __construct(array $db_info/*=DEFAULT_DATABASE_INFO_CONSTANTS::$db_info*/){
		$servername = $db_info[SERVERNAME];		//服务器地址
		$username = $db_info[USERNAME];			//服务器用户名
		$passwd = $db_info[PASSWD];				//服务器密码
		$dbname = $db_info[DBNAME];				//数据库名
		
		//新建日志类
		$this->cLog = new myLog();
		//连接到数据库源
		$this->con = mysql_connect($servername, $username, $passwd) or die(mysql_error());
		
		//打开数据库
		$result = mysql_select_db($dbname, $this->con) or die(mysql_error());
	
		$this->cLog->add(__FILE__.":open database!");
	}

	public function query($sql){
		mysql_query("SET NAMES GBK", $this->con);			//保证可以查询匹配中文，对应数据库表上的gbk_chinese_ci类型
		//mysql_query("SET NAMES UTF8", $this->con);			//保证可以查询匹配中文
		$result = mysql_query($sql, $this->con) or die(mysql_error());
		$this->cLog->add(__FILE__.":query!");
		return $result;
	}
	function __destruct(){
		$result = mysql_close($this->con) or die(mysql_error());
		$this->cLog->add(__FILE__.":close database!");
	}
	public function get_con(){
		return $this->con;
	}
	public function get_cLog(){
		return $this->cLog;
	}
}

?>