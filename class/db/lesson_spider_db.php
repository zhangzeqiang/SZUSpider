<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
require_once(ROOT_PATH."\\Spider\\class\\db\\database.php");
require_once(ROOT_PATH.'\\Spider\\class\\log_class.php');		//������־��

/**
 * constant class define.
 */
class lesson_spider_db_Constants{
	/**
	 * table define.
	 */
	public static $table = array(
		teacher => "teacher",
		lesson => "lesson",
		college => "college",
		major_class => "class",
		class_lesson => "class_lesson",
		room_time_lesson => "room_time_lesson",
		student => "student",
		stu_tea_lesson => "stu_tea_lesson"
	);
}
class college_lesson_spider_db{
	private $con;
	private $db;
	private $cLog;
	function __construct($db){
		
		$this->db = $db;
		$this->cLog = $db->get_cLog();									//��ȡ���ݿ���־����
		$this->con = $db->get_con();									//��ȡ���ݿ�����������
		$this->cLog->add(__FILE__.":new college_lesson_spider_db");		//��־
		
	}
	public function query($sql){
		return $this->db->query($sql);
	}
	/** 
	 * insert the all userful information into database in the college_lesson page. 
	 * @param array $list (type is define in inc.php --- user should deliver this type to this function.)
	 * @return bool false(some information has been inserted into the corresponding table.) 
	 * or $id(the lesson_id).
	 */ 
	public function insert_all(array $list){
		
		$this->cLog->add(__FILE__.":prepare to insert_all.");
		/*$lesson_list = array(
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
		weeks => ,
		lesson_time => "",
		room => "",
		credit_type => "",
		other => "",

		term_no => ""			//������һ��term_no
		);*/
		//$teacher_id_list = self::insert_teacher($list[teacher]);			//�����ʦ��Ϣ,��ȡ��ʦid(array)
		
		$college_no = self::insert_college($list[college]);				//����ѧԺ��Ϣ,��ȡѧԺid
		$this->cLog->add(__FILE__.":college info insert.");
		
		//�鿴�Ƿ��Ѿ����ڶ�Ӧ�Ŀγ�
		$sql = "select count(*) from %s where lesson_no='%s' and term_no='%s'";
		$sql = sprintf($sql, lesson_spider_db_Constants::$table[lesson], $list[lesson_no], $list[term_no]);

		$result = $this->query($sql);
		$count = -1;
		while ($row = mysql_fetch_array($result)){
			if ($row[0]){
				$count = $row[0];
			}
			break;
		}
		if (-1 == $count){			//��������ڼ�¼�������γ���Ϣ
			$sql = "insert into %s(lesson_no,term_no,name,credit,limit_person,college_no,class_type,credit_type,weeks,other) values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[lesson], $list[lesson_no], $list[term_no], $list[lesson_name],
				 $list[credit], $list[limit_person], $college_no, $list[lesson_type], $list[credit_type], $list[weeks], $list[other]);
			$this->query($sql);
			$this->cLog->add(__FILE__.":lesson record does not exist,insert it.");
		}
		//(ͨ��lesson_no,term_no)��ȡ�γ�id
		$sql = "select id from %s where lesson_no='%s' and term_no='%s'";
		$sql = sprintf($sql, lesson_spider_db_Constants::$table[lesson], $list[lesson_no], $list[term_no]);
		$result = $this->query($sql);
		$lesson_id = -1;
		while ($row = mysql_fetch_array($result)){
			$lesson_id = $row["id"];			
			break;
		}
		if (-1 == $lesson_id)	return false;

		$this->cLog->add(__FILE__.":Get lesson_id.");

		//������ѡ�༶��Ϣ,��ȡ�༶id(array)
		$class_id_list = self::insert_major_class($list[major_class]);
		$this->cLog->add(__FILE__.":Get mojor class id.");

		//����class_lesson(class_id,lesson_id)��Ϣ
		$i = 0;
		for ($i=0;$i<count($class_id_list);$i++){
			//�Ȳ�ѯ����������ڼ�¼�����
			$sql = "select count(*) from %s where lesson_id='%s' and class_id='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[class_lesson], $lesson_id, $class_id_list[$i]);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){
				if (!$row[0]){				//����¼������
					//����
					$sql = "insert into %s(lesson_id,class_id) values('%s','%s')";
					$sql = sprintf($sql, lesson_spider_db_Constants::$table[class_lesson], $lesson_id, $class_id_list[$i]);
					$this->query($sql);
				}
			}
		}
		$this->cLog->add(__FILE__.":insert major classes id.");
		//����room_time_lesson(room,time,lesson_id)
		self::insert_room_time($list[lesson_time], $list[room], $lesson_id);

		$this->cLog->add(__FILE__.":finish insert_all.");
		return $lesson_id;
	}
	/** 
	 * insert the major_class_list to the corresponding table.  
	 * function:explode the sMajor_class to class_list and add those to table.
	 * @param String $sMajor_class @.eg{2011ͨ�Ź���01/2011ͨ�Ź���02/2011ͨ�Ź���03};
	 * @return array $id insert successfully.
	 */ 
	private function insert_major_class($sMajor_class){
		
		$this->cLog->add(__FILE__.":prepare to insert ".$sMajor_class.".");

		//���Ƚ�$sMajor_class���зָ�
		//$class_list = explode("/", $sMajor_class);
		$class_list = split("[;\\/]", $sMajor_class);

		$class_count = count($class_list);
		for($i=0;$i<$class_count;$i++){
			//�Ȳ鿴���ݿ⣬���û�ж�Ӧ��¼,������¼
			$sql = "select count(*) from %s where name='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[major_class], $class_list[$i]);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){
				if (!$row[0]){				//����¼������

					$this->cLog->add(__FILE__.":Could not find ".$class_list[$i]." and go to insert it.");
					$sql = "insert into %s(name) values('%s')";
					$sql = sprintf($sql, lesson_spider_db_Constants::$table[major_class], $class_list[$i]);
					$this->query($sql);			//��������
				}
				$this->cLog->add(__FILE__.":The record with ".$class_list[$i]." have exist.");

				//��ȡid������
				$sql = "select id from %s where name='%s'";
				$sql = sprintf($sql, lesson_spider_db_Constants::$table[major_class], $class_list[$i]);
				$result = $this->query($sql);
				while ($row = mysql_fetch_array($result)){
					$this->cLog->add(__FILE__.":Get id from ".lesson_spider_db_Constants::$table[major_class]."  satisfy the condition.");
					$id[$i] = $row["id"];	
				}
			}
		}
		$this->cLog->add(__FILE__.":finish to insert ".$sMajor_class.".");
		return $id;

	}
	/** 
	 * insert the rooms_and_times to the corresponding table.  
	 * function:after explode the $sTime and $sRoom string to time_list and room_list, add those to table with some order
	 * like @.eg{C307;ʵ��(D506(����)),����1,2;����1,2} then (C307=>����1,2=>$lesson_id), (ʵ��(D506(����)=>����1,2=>$lesson_id) to form two records.
	 * @param String $sTime
	 * @param String $sRoom
	 * @param $lesson_id
	 * @return void
	 */ 
	private function insert_room_time($sTime, $sRoom, $lesson_id){					//�����Ͽ�ʱ��ص�

		$this->cLog->add(__FILE__.":prepare to insert ".$sTime." and ".$sRoom.".");

		//$time_list = explode(";", $sTime);							//��ʱ���ַ����ָ�
		//$room_list = explode(";", $sRoom);							//���ص��ַ����ָ�
		$time_list = split("[;\\/]", $sTime);							//��ʱ���ַ����ָ�
		$room_list = split("[;\\/]", $sRoom);							//���ص��ַ����ָ�

		$time_count = count($time_list);
		$room_count = count($room_list);

		$count = $time_count>$room_count?$room_count:$time_count;	//ѡ������֮����Сֵ
		for ($i=0;$i<$count;$i++){
			//�Ȳ鿴���ݿ⣬���û�ж�Ӧ��¼,������¼
			$sql = "select count(*) from %s where time='%s' and room='%s' and lesson_id='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[room_time_lesson], $time_list[$i], $room_list[$i], $lesson_id);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){

				if (!$row[0]){				//����¼������
					$this->cLog->add(__FILE__.":Could not find record in room_time_lesson table and go to insert record.");
					$sql = "insert into %s(time,room,lesson_id) values('%s','%s','%s')";
					$sql = sprintf($sql, lesson_spider_db_Constants::$table[room_time_lesson], $time_list[$i], $room_list[$i], $lesson_id);
					$this->query($sql);			//��������
				}
				$this->cLog->add(__FILE__.":The record has existed.");
				break;
			}
		}
		mysql_free_result($result);			//�ͷ��ڴ�
		
		$this->cLog->add(__FILE__.":finish to insert ".$sTime." and ".$sRoom.".");

	}
	/** 
	 * insert the college info to the corresponding table.  
	 * @param $college 
	 * @return $id if insert successfully.
	 */ 
	private function insert_college($college){

		$this->cLog->add(__FILE__.":prepare to insert ".$college.".");

		//�Ȳ鿴���ݿ⣬���û�ж�Ӧ��¼,������¼
		$sql = "select count(*) from %s where name='%s'";
		$sql = sprintf($sql, lesson_spider_db_Constants::$table[college], $college);
		$result = $this->query($sql);
		while ($row = mysql_fetch_array($result)){
			if (!$row[0]){				//����¼������

				$this->cLog->add(__FILE__.":Could not find ".$college." and go to insert it.");
				$sql = "insert into %s(name) values('%s')";
				$sql = sprintf($sql, lesson_spider_db_Constants::$table[college], $college);
				$result = $this->query($sql);			//��������
			}
			$this->cLog->add(__FILE__.":The record with ".$college." have exist.");

			//��ȡid������
			$sql = "select id from %s where name='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[college], $college);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){
				$this->cLog->add(__FILE__.":Get id from ".lesson_spider_db_Constants::$table[college]."  satisfy the condition.");
				return $row["id"];	
			}
		}
		mysql_free_result($result);			//�ͷ��ڴ�

		$this->cLog->add(__FILE__.":finish insert ".$college.".");

	}
	/** 
	 * insert the teacher info to the corresponding table.  
	 * @param String $sTeacher  @.eg{����/С��}
	 * @return array $id if insert successfully.
	 */ 
	public function insert_teacher($sTeacher){
		$this->cLog->add(__FILE__.":prepare to insert ".$sTeacher.".");

		//���Ƚ�$sTeacher�ָ�
		//$teacher_list = explode("/", $sTeacher);
		$teacher_list = split("[;\\/]", $sTeacher);

		$teacher_count = count($teacher_list);
		for($i=0;$i<$teacher_count;$i++){
			//�Ȳ鿴���ݿ⣬���û�ж�Ӧ��¼,������¼
			$sql = "select count(*) from %s where name='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[teacher], $teacher_list[$i]);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){
				if (!$row[0]){				//����¼������

					$this->cLog->add(__FILE__.":Could not find ".$teacher_list[$i]." and go to insert it.");
					$sql = "insert into %s(name) values('%s')";
					$sql = sprintf($sql, lesson_spider_db_Constants::$table[teacher], $teacher_list[$i]);
					$result = $this->query($sql);			//��������
				}
				$this->cLog->add(__FILE__.":The record with ".$teacher_list[$i]." have exist.");

				//��ȡid������
				$sql = "select id from %s where name='%s'";
				$sql = sprintf($sql, lesson_spider_db_Constants::$table[teacher], $teacher_list[$i]);
				$result = $this->query($sql);
				while ($row = mysql_fetch_array($result)){
					$this->cLog->add(__FILE__.":Get id from ".lesson_spider_db_Constants::$table[teacher]."  satisfy the condition.");
					$id[$i] = $row["id"];	
				}
			}
		}
		return $id;
		$this->cLog->add(__FILE__.":finish to insert ".$sTeacher.".");
	}
	function __destruct(){

		$this->cLog->add(__FILE__.":close lesson_spider_db.");
	}
}

class lesson_student_spider_db{

	private $con;
	private $db;
	private $cLog;

	function __construct($db){

		$this->db = $db;
		$this->cLog = $db->get_cLog();
		$this->con = $db->get_con();

		$this->cLog->add(__FILE__.":new lesson_student_spider_db");		//��־

	}
	public function query($sql){
		return $this->db->query($sql);
	}
	function insert_all($list){
		/*echo "insert<br>";
		print_r($list);*/
		//���Ȳ鿴�Ƿ��Ѿ�����ѧ����Ϣ
		$sql = "select count(*) from %s where id='%s'";
		$sql = sprintf($sql,lesson_spider_db_Constants::$table[student],$list['student_no']);
		$result = $this->query($sql);
		while ($row = mysql_fetch_array($result)){
			if (!$row[0]){				//����¼������

				$this->cLog->add(__FILE__.":Could not find ".$list['student_no']." and go to insert it.");
				$sql = "insert into %s(id,name,major,sex) values('%s','%s','%s','%s')";
				$sql = sprintf($sql, lesson_spider_db_Constants::$table[student], $list['student_no'],$list['name'],$list['major'],$list['sex']);
				$this->query($sql);			//��������
			}
			
			$this->cLog->add(__FILE__.":The record with ".$list['student_no']." have exist.");
			break;
		}
		
		$teacher_id_list = self::insert_teacher($list['teacher']);			//�����ʦ��Ϣ,��ȡ��ʦid(array)\
		
		//(ͨ��lesson_no,term_no)��ȡ�γ�id
		$sql = "select id from %s where lesson_no='%s' and term_no='%s'";
		$sql = sprintf($sql, lesson_spider_db_Constants::$table[lesson], $list['lesson_no'], $list['term_no']);
		$result = $this->query($sql);
		$lesson_id = -1;
		while ($row = mysql_fetch_array($result)){
			$lesson_id = $row["id"];			
			break;
		}
		if (-1 == $lesson_id)	return false;

		//����stu_tea_lesson��
		//student_no:$list[student_no],teacher_no:$teacher_id_list,lesson_id
		$teacher_count = count($teacher_id_list);
		$i = 0;
		for ($i=0;$i<$teacher_count;$i++){
			//�鿴��¼�Ƿ����
		
			$sql = "select count(*) from %s where lesson_id='%s'and teacher_no='%s' and student_no='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[stu_tea_lesson], $lesson_id, $teacher_id_list[$i], $list['student_no']);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){
				if (!$row[0]){
					//��¼�����ڣ�������¼
					$sql = "insert into %s(lesson_id,teacher_no,student_no) values('%s','%s','%s')";
					$sql = sprintf($sql, lesson_spider_db_Constants::$table[stu_tea_lesson], $lesson_id, $teacher_id_list[$i], $list['student_no']);
					$this->query($sql);
				}
			}
		}
	}
	/** 
	 * insert the teacher info to the corresponding table.  
	 * @param String $sTeacher  @.eg{����/С��}
	 * @return array $id if insert successfully.
	 */ 
	public function insert_teacher($sTeacher){
		$this->cLog->add(__FILE__.":prepare to insert ".$sTeacher.".");

		//���Ƚ�$sTeacher�ָ�
		//$teacher_list = explode("/", $sTeacher);
		$teacher_list = split("[;\\/]", $sTeacher);

		$teacher_count = count($teacher_list);
		for($i=0;$i<$teacher_count;$i++){
			//�Ȳ鿴���ݿ⣬���û�ж�Ӧ��¼,������¼
			$sql = "select count(*) from %s where name='%s'";
			$sql = sprintf($sql, lesson_spider_db_Constants::$table[teacher], $teacher_list[$i]);
			$result = $this->query($sql);
			while ($row = mysql_fetch_array($result)){
				if (!$row[0]){				//����¼������

					$this->cLog->add(__FILE__.":Could not find ".$teacher_list[$i]." and go to insert it.");
					$sql = "insert into %s(name) values('%s')";
					$sql = sprintf($sql, lesson_spider_db_Constants::$table[teacher], $teacher_list[$i]);
					$result = $this->query($sql);			//��������
				}
				$this->cLog->add(__FILE__.":The record with ".$teacher_list[$i]." have exist.");

				//��ȡid������
				$sql = "select id from %s where name='%s'";
				$sql = sprintf($sql, lesson_spider_db_Constants::$table[teacher], $teacher_list[$i]);
				$result = $this->query($sql);
				while ($row = mysql_fetch_array($result)){
					$this->cLog->add(__FILE__.":Get id from ".lesson_spider_db_Constants::$table[teacher]."  satisfy the condition.");
					$id[$i] = $row["id"];	
				}
			}
		}
		return $id;
		$this->cLog->add(__FILE__.":finish to insert ".$sTeacher.".");
	}
	function __destruct(){

		$this->cLog->add(__FILE__.":close lesson_spider_db.");
	}
}

?>