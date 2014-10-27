<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );					//��ȡ��Ŀ¼
}
//��һ��:����ȡѧԺ��ҳ
require_once(ROOT_PATH."\\Spider\\include\\spider.func.php");
set_time_limit(0);
get_college_page(20131);
//�ڶ���:�Ƚ�ѧԺ_�γ����ݲ������ݿ⡣
/*set_time_limit(0);
insert_college_lesson_in_term("20132");*/

//������:��ȫ���γ�_ѧ�����ݲ������ݿ⡣
require_once(ROOT_PATH."\\Spider\\include\\inc.php");
require_once(ROOT_PATH."\\Spider\\include\\spider.func.php");
require_once(ROOT_PATH."\\Spider\\class\\db\\lesson_spider_db.php");
require_once(ROOT_PATH."\\Spider\\class\\db\\database.php");				//ʹ�����ַ�ʽ�����ļ��Ų������

set_time_limit(0);
$db = new cDatabase(DATABASE_INFO_CONSTANTS::$db_info);
$college_lesson_db = new college_lesson_spider_db($db);			//ע���������û�йرյĻ����������ִ���
$lesson_db = new lesson_student_spider_db($db);					//�򿪿γ�ѧ�����ݿ������

$log = new myLog();
$log->setMode(0);								//������־�����

$my_dir = "resource/all_info_20132/";
$handler = opendir($my_dir);
while (($filename = readdir($handler)) !== false){
	if ($filename == "." || $filename == ".."){
		continue;		
	}
	$lesson_list = explode("_",$filename);
	$_lesson_no = $lesson_list[0];

	//$_lesson_no = $mylist[lesson_no];
	$_term_no = "20132";
	/*$_filepl = "resource/class_%s/%s.txt";
	$_file = sprintf($_filepl, $_term_no, $_lesson_no);*/
	$_file = $my_dir.$filename;
	//echo $_file."<br>";
	//exit;
	$_code = file_get_contents($_file);
	//echo $_code;
	$log->add(__FILE__.":open ".$_lesson_no.".txt of college:".$mylist[college]);	//�򿪿γ�ѧ���ļ�

	$_code = explode("��&nbsp;", $_code);
	$_code = $_code[1];												//ͨ���۲��������Կ��Կ���"��&nbsp;1&nbsp;ҳ"��Դ��ֳ������֣���һ���ֵ���Ϣ����������Ҫ�ģ�����ȥ��.

	$_mode = array(
		FIRST => ("div id=\"Section3\""),
		SECOND => "div",
		LAST => ""
	);
	$_list = get_attr_lists($_code, $_mode);
	//print_r($_list);
	$_row_count = count($_list[0]);		//����
	$_col = 0;
	$_col_count = count($_list);			//����
	//echo "row:".$_row_count."<br>";
	//echo "col:".$_col_count."<br>";
	for ($_col=0;$_col<$_col_count;$_col++){						//����γ�ѧ����Ϣҳ�����ѧ����Ϣ
		$_row = 0;
		for($_row=0;$_row<$_row_count;$_row++){
			//echo $_list[$_col][$_row]." ";
			//echo $_list[$_col][1]." ";
			//$student_list['id']=$_list[$_col][id];
			$student_list['student_no']=$_list[$_col][student_no];
			$student_list['name']=$_list[$_col][name];
			$student_list['sex']=$_list[$_col][sex];
			$student_list['major']=$_list[$_col][major];
			$student_list['lesson_no']=$_lesson_no;
			$student_list['term_no']=$_term_no;
			$student_list['teacher']=$mylist[teacher];
			
			//print_r($student_list);
			//echo "<br>";
			$lesson_db->insert_all($student_list);					//����γ�ѧ����Ϣ
			//$lesson_db =  null;								//�ر����ݿ�Դ��������ܵ���
		}
	}
	//exit;	//insert related to first lesson_no
}
/**
 * insert college=>lesson,lesson=>student two type pages info.
 * @param $term_no.
 * @param $log_mode 1(output nots) 0(not output).
 * notice:local server should already have the web code file(college_lesson, lesson_student).
 * use the function in spider.func.php to get them first.
 * ��������Ǹ�����ȡ�γ�ҳ��ѧ����ϢԴ����ҳ(����ֻ������ÿ���γ�ҳ�ĵ�һҳ)
 */
function insert_all_college_lesson_student_in_term($term_no, $log_mode='1'){
	require_once(ROOT_PATH."\\Spider\\include\\inc.php");
	require_once(ROOT_PATH."\\Spider\\include\\spider.func.php");
	require_once(ROOT_PATH."\\Spider\\class\\db\\lesson_spider_db.php");
	require_once(ROOT_PATH."\\Spider\\class\\db\\database.php");				//ʹ�����ַ�ʽ�����ļ��Ų������

	set_time_limit(0);
	$db = new cDatabase(DATABASE_INFO_CONSTANTS::$db_info);
	$college_lesson_db = new college_lesson_spider_db($db);			//ע���������û�йرյĻ����������ִ���
	$lesson_db = new lesson_student_spider_db($db);					//�򿪿γ�ѧ�����ݿ������

	$log = new myLog();
	$log->setMode($log_mode);								//������־�����

	foreach($college_list as $college){						//��ȡ��ͬѧԺ����ҳ������Ϣ
		$filepl = "resource/college_%s/%s.txt";
		$file = sprintf($filepl, $term_no, $college);
		$code = file_get_contents($file);
		$mode = array(
		FIRST=>"tr", 
		SECOND=>"td", 
		LAST=>""
		);
		$list = get_attr_lists($code, $mode);
		//print_r($list);
		$row_count = count($list[0]);
		$col = 0;
		$col_count = count($list);
		$log->add(__FILE__.":open ".$college.".txt");		//��ѧԺ�ļ�

		//echo "count:".$col_count."<br>";
		for($col=0;$col<$col_count;$col++){
			if ($col == 0){
				continue;
			}
			if($col >= ($col_count-1)){
				//echo "<br>";
				break;
			}
			//echo "col:".$col."<br>";
			//�����ݱ��浽���ݿ���
			$mylist = $college_lesson_list;
			$mylist[id] = $list[$col][id];
			$mylist[college] = $list[$col][college];
			$mylist[teacher] = $list[$col][teacher];
			$mylist[lesson_time] = $list[$col][lesson_time];
			$mylist[major_class] = $list[$col][major_class];
			$mylist[lesson_no] = $list[$col][lesson_no];
			$mylist[lesson_name] = $list[$col][lesson_name];
			$mylist[credit] = $list[$col][credit];
			$mylist[total_person] = $list[$col][total_person];
			$mylist[limit_person] = $list[$col][limit_person];
			$mylist[lesson_type] = $list[$col][lesson_type];
			$mylist[weeks] = $list[$col][weeks];
			$mylist[room] = $list[$col][room];
			$mylist[credit_type] = $list[$col][credit_type];
			$mylist[other] = $list[$col][other];
			
			$mylist[term_no] = $term_no;
			
			//print_r($mylist);
			
			$college_lesson_db->insert_all($mylist);						//���ȱ���ѧԺ����ҳ������Ϣ
			//$college_lesson_db = null;									//�ر����ݿ�Դ��������ܴ����ݿ�Դ

			//echo "�γ̱��".$mylist[lesson_no]."<br>";
			//Ȼ�����lesson_studentҳ
			$_lesson_no = $mylist[lesson_no];
			$_term_no = $term_no;
			$_filepl = "resource/class_%s/%s.txt";
			$_file = sprintf($_filepl, $_term_no, $_lesson_no);
			$_code = file_get_contents($_file);
			//echo $_code;
			$log->add(__FILE__.":open ".$_lesson_no.".txt of college:".$mylist[college]);	//�򿪿γ�ѧ���ļ�
			
			$_code = explode("��&nbsp;", $_code);
			$_code = $_code[1];												//ͨ���۲��������Կ��Կ���"��&nbsp;1&nbsp;ҳ"��Դ��ֳ������֣���һ���ֵ���Ϣ����������Ҫ�ģ�����ȥ��.
			
			$_mode = array(
				FIRST => ("div id=\"Section3\""),
				SECOND => "div",
				LAST => ""
			);
			$_list = get_attr_lists($_code, $_mode);
			//print_r($_list);
			$_row_count = count($_list[0]);		//����
			$_col = 0;
			$_col_count = count($_list);			//����
			//echo "row:".$_row_count."<br>";
			//echo "col:".$_col_count."<br>";
			for ($_col=0;$_col<$_col_count;$_col++){						//����γ�ѧ����Ϣҳ�����ѧ����Ϣ
				$_row = 0;
				for($_row=0;$_row<$_row_count;$_row++){
					//echo $_list[$_col][$_row]." ";
					//echo $_list[$_col][1]." ";
					//$student_list['id']=$_list[$_col][id];
					$student_list['student_no']=$_list[$_col][student_no];
					$student_list['name']=$_list[$_col][name];
					$student_list['sex']=$_list[$_col][sex];
					$student_list['major']=$_list[$_col][major];
					$student_list['lesson_no']=$_lesson_no;
					$student_list['term_no']=$_term_no;
					$student_list['teacher']=$mylist[teacher];
					
					//print_r($student_list);
					//echo "<br>";
					$lesson_db->insert_all($student_list);					//����γ�ѧ����Ϣ
					//$lesson_db =  null;								//�ر����ݿ�Դ��������ܵ���
				}
			}
			//exit;	//insert related to first lesson_no
		}
		//exit;		//insert related to first college.
	}
}
/**
 * insert college=>lesson one type pages info(no include lesson=>student pages info).
 * @param $term_no.
 * @param $log_mode 1(output nots) 0(not output).
 * notice:local server should already have the web code file(college_lesson file).
 * use the function in spider.func.php to get them first.
 */
function insert_college_lesson_in_term($term_no, $log_mode='1'){
	require_once(ROOT_PATH."\\Spider\\include\\inc.php");
	require_once(ROOT_PATH."\\Spider\\include\\spider.func.php");
	require_once(ROOT_PATH."\\Spider\\class\\db\\lesson_spider_db.php");
	
	$db = new cDatabase(DATABASE_INFO_CONSTANTS::$db_info);
	$college_lesson_db = new college_lesson_spider_db($db);			//ע���������û�йرյĻ����������ִ���
	$log = new myLog();
	$log->setMode($log_mode);

	foreach($college_list as $college){
		$filepl = "resource/college_%s/%s.txt";
		$file = sprintf($filepl, $term_no, $college);
		$code = file_get_contents($file);
		$mode = array(
		FIRST=>"tr", 
		SECOND=>"td", 
		LAST=>""
		);
		$list = get_attr_lists($code, $mode);
		//print_r($list);
		$row_count = count($list[0]);
		$col = 0;
		$col_count = count($list);
		$log->add(__FILE__.":open ".$college.".txt");
		for($col=0;$col<$col_count;$col++){
			if ($col == 0){
				continue;
			}
			if($col >= ($col_count-1)){
				//echo "<br>";
				break;
			}
			//�����ݱ��浽���ݿ���
			$mylist = $college_lesson_list;
			$mylist[id] = $list[$col][id];
			$mylist[college] = $list[$col][college];
			$mylist[teacher] = $list[$col][teacher];
			$mylist[lesson_time] = $list[$col][lesson_time];
			$mylist[major_class] = $list[$col][major_class];
			$mylist[lesson_no] = $list[$col][lesson_no];
			$mylist[lesson_name] = $list[$col][lesson_name];
			$mylist[credit] = $list[$col][credit];
			$mylist[total_person] = $list[$col][total_person];
			$mylist[limit_person] = $list[$col][limit_person];
			$mylist[lesson_type] = $list[$col][lesson_type];
			$mylist[weeks] = $list[$col][weeks];
			$mylist[room] = $list[$col][room];
			$mylist[credit_type] = $list[$col][credit_type];
			$mylist[other] = $list[$col][other];
			
			$mylist[term_no] = $term_no;
			
			$college_lesson_db->insert_all($mylist);
		}
	}
}

/*
delete from class;
delete from class_lesson;
delete from college;
delete from room_time_lesson;
delete from teacher;
delete from lesson;
delete from student;
delete from stu_tea_lesson;
*/
?>