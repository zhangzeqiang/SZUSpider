<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
require_once(ROOT_PATH.'\\Spider\\class\\log_class.php');		//������־��
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
		$servername = $db_info[SERVERNAME];		//��������ַ
		$username = $db_info[USERNAME];			//�������û���
		$passwd = $db_info[PASSWD];				//����������
		$dbname = $db_info[DBNAME];				//���ݿ���
		
		//�½���־��
		$this->cLog = new myLog();
		//���ӵ����ݿ�Դ
		$this->con = mysql_connect($servername, $username, $passwd) or die(mysql_error());
		
		//�����ݿ�
		$result = mysql_select_db($dbname, $this->con) or die(mysql_error());
	
		$this->cLog->add(__FILE__.":open database!");
	}

	public function query($sql){
		mysql_query("SET NAMES GBK", $this->con);			//��֤���Բ�ѯƥ�����ģ���Ӧ���ݿ���ϵ�gbk_chinese_ci����
		//mysql_query("SET NAMES UTF8", $this->con);			//��֤���Բ�ѯƥ������
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