<?php
class DATABASE_INFO_CONSTANTS{
	public static $db_info = array(
	SERVERNAME => "localhost",
	USERNAME => "root",
	PASSWD => "",
	DBNAME => "cenker"
	);
}
$college_list = array(
"传播学院",
"大学英语教学部",
"电子科学与技术学院",
"法学院",
"高尔夫学院",
"高级研究中心",
"管理学院",
"光电工程学院",
"国际交流学院",
"化学与化工学院",
"机电与控制工程学院",
"计算机与软件学院",
"建筑与城市规划学院",
"经济学院",
"社会科学学院",
"生命科学学院",
"师范学院",
"数学与计算科学学院",
"体育部",
"图书馆",
"土木工程学院",
"外国语学院",
"文学院",
"武装部",
"物理科学与技术学院",
"校团委",
"信息工程学院",
"学生部",
"医学院",
"艺术设计学院",
"招生就业办公室",
"中国经济特区研究中心"
);
/**
 * college page attribute define.
 */
define ("id" , "0");
define ("lesson_no" , "1");
define ("lesson_name" , "2");
define ("credit" , "3");
define ("total_person" , "4");
define ("limit_person" , "5");
define ("college" , "6");
define ("lesson_type" , "7");
define ("major_class" , "8");
define ("teacher" , "9");
define ("weeks" , "10");
define ("lesson_time" , "11");
define ("room" , "12");
define ("credit_type" , "13");
define ("other" , "14");
/**
 *
 */
define ("term_no", "15");
/**
 *
 */
$college_lesson_list = array(
	id => "",
	lesson_no => "",
	lesson_name => "",
	credit => "",
	total_person => "",
	limit_person => "",
	college => "",
	lesson_type => "",
	major_class => "",
	teacher => "",
	weeks => "",
	lesson_time => "",
	room => "",
	credit_type => "",
	other => "",

	term_no => ""			//增加了一个term_no
);
/**
 * lesson_student define
 */
$lesson_student_list = array(
	id => "",				//网页中的内容
	student_no => "",
	name => "",
	sex => "",
	major => "",
	free_to_listen => "",	//是否免听

	lesson_no => "",		//增加的字段
	term_no => "",
	teacher => ""
);
define("student_no", "0");
define("name", "1");
define("sex", "2");
define("major", "3");
//notice 字符索引数组和常量索引数组，使用的时候要注意区别，否则会出现很多问题
?>