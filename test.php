<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
set_time_limit(0);
getAllLessonStudentWeb();

/*
 * ���ݱ������Ѿ���ȡ���Ŀγ�ѧ��ҳ��(��һ��ҳ��)��ȡʣ���ȫ��ҳ��Դ��
 */
function getAllLessonStudentWeb(){				//��ͬѧ����ͬ�γ̱��û����
	require_once(ROOT_PATH."\\Spider\\class\\db\\database.php");
	//��ȡ�ļ���
	$my_dir = "resource/class_20132/";
	$handler = opendir($my_dir);
	$m = 0;
	$db = new cDatabase(DEFAULT_DATABASE_INFO_CONSTANTS::$db_info);
	while (($filename = readdir($handler)) !== false){
		if ($filename == "." || $filename == ".."){
			continue;		
		}
		$m++;
		$i = 0;
		if ($i == 0){
			//��ȡ�γ̱��
			//$lesson_no = "0100970001";
			$lesson_list = explode(".txt", $filename);
			$lesson_no = $lesson_list[0];
			//��ȡ�ļ�Դ��
			$file = $my_dir.$lesson_no.".txt";

			//�����ݿ����ҳ���ѡ�����������ҳ��
			$sql = "select limit_person from lesson where lesson_no='%s'";
			$sql = sprintf($sql, $lesson_no);
			//echo $sql."<br>";
			$result = $db->query($sql);
			$row=mysql_fetch_array($result);

			if ($row['limit_person']){
				$limit = $row['limit_person'];
			}
			//echo $limit."<br>";

			if ($limit){
				$total_page = floor($limit/27)+1;
			}else{
				$total_page = 0;
			}
		}
		$current_page = 1;
		for ($current_page=1;$current_page <= $total_page;$current_page++){
			$post_data = array (
			"__VIEWSTATE" => "",
			"__EVENTVALIDATION" => "",
			'crvDjb$ctl02$ctl06.x' => "14",
			'crvDjb$ctl02$ctl06.y' => "11");			//ǰ�������ֶξ�������ת��ҳ�棬���������ֶο���㣬���ǲ�����

			$post = $post_data;
			if ($current_page == 1){
				$result = file_get_contents($file);
			}else {
				$result = get_lesson_student_web($post);
			}
			$dir = "resource/all_info_20132";
			if (!is_dir($dir)){
				mkdir($dir);
			}
			$lesson_file = $dir."/".$lesson_no."_".$current_page.".txt";
			
			$fp = fopen($lesson_file, "a");
			fwrite($fp, $result);
			fclose($fp);
		}
		if ($m > 5){
			break;	
		}
	}
	closedir($handler);
}
/*
 * ����һ�������$post�ֶη��ؿγ�ѧ����ϢԴ��
 */
function get_lesson_student_web($post_data){
	
	$url = 'http://192.168.2.229/newkc/djbprint.aspx?xqh=20132&ykch=20001002';
	$sendip="192.68.177.1";
	
	$ch = curl_init(); //��ʼ��
	curl_setopt($ch, CURLOPT_POST, 1);
	// ��post�ı�������
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$sendip, 'CLIENT-IP:'.$sendip));  
	curl_setopt($ch, CURLOPT_URL, $url); 
	//curl_setopt($ch, CURLOPT_REFERER,"http://192.168.2.229/newkc/djbprint.aspx?xqh=20122&ykch=2112000102"); //α����·ҳ��
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0"); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //�Ƿ���ʾ����
	$result=curl_exec($ch); //ִ��
	
	curl_close($ch);
	return $result;
}
?>