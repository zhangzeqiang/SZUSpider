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
"����ѧԺ",
"��ѧӢ���ѧ��",
"���ӿ�ѧ�뼼��ѧԺ",
"��ѧԺ",
"�߶���ѧԺ",
"�߼��о�����",
"����ѧԺ",
"��繤��ѧԺ",
"���ʽ���ѧԺ",
"��ѧ�뻯��ѧԺ",
"��������ƹ���ѧԺ",
"����������ѧԺ",
"��������й滮ѧԺ",
"����ѧԺ",
"����ѧѧԺ",
"������ѧѧԺ",
"ʦ��ѧԺ",
"��ѧ������ѧѧԺ",
"������",
"ͼ���",
"��ľ����ѧԺ",
"�����ѧԺ",
"��ѧԺ",
"��װ��",
"�����ѧ�뼼��ѧԺ",
"У��ί",
"��Ϣ����ѧԺ",
"ѧ����",
"ҽѧԺ",
"�������ѧԺ",
"������ҵ�칫��",
"�й����������о�����"
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

	term_no => ""			//������һ��term_no
);
/**
 * lesson_student define
 */
$lesson_student_list = array(
	id => "",				//��ҳ�е�����
	student_no => "",
	name => "",
	sex => "",
	major => "",
	free_to_listen => "",	//�Ƿ�����

	lesson_no => "",		//���ӵ��ֶ�
	term_no => "",
	teacher => ""
);
define("student_no", "0");
define("name", "1");
define("sex", "2");
define("major", "3");
//notice �ַ���������ͳ����������飬ʹ�õ�ʱ��Ҫע�����𣬷������ֺܶ�����
?>