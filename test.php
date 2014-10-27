<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
set_time_limit(0);
getAllLessonStudentWeb();

/*
 * 根据本地上已经爬取到的课程学生页面(第一张页面)爬取剩余的全部页面源码
 */
function getAllLessonStudentWeb(){				//不同学期相同课程编号没考虑
	require_once(ROOT_PATH."\\Spider\\class\\db\\database.php");
	//获取文件名
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
			//获取课程编号
			//$lesson_no = "0100970001";
			$lesson_list = explode(".txt", $filename);
			$lesson_no = $lesson_list[0];
			//获取文件源码
			$file = $my_dir.$lesson_no.".txt";

			//从数据库中找出限选人数，计算出页数
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
			'crvDjb$ctl02$ctl06.y' => "11");			//前面两个字段决定了跳转的页面，后面两个字段可随便，但是不可无

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
 * 根据一个特殊的$post字段返回课程学生信息源码
 */
function get_lesson_student_web($post_data){
	
	$url = 'http://192.168.2.229/newkc/djbprint.aspx?xqh=20132&ykch=20001002';
	$sendip="192.68.177.1";
	
	$ch = curl_init(); //初始化
	curl_setopt($ch, CURLOPT_POST, 1);
	// 把post的变量加上
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$sendip, 'CLIENT-IP:'.$sendip));  
	curl_setopt($ch, CURLOPT_URL, $url); 
	//curl_setopt($ch, CURLOPT_REFERER,"http://192.168.2.229/newkc/djbprint.aspx?xqh=20122&ykch=2112000102"); //伪造来路页面
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0"); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //是否显示内容
	$result=curl_exec($ch); //执行
	
	curl_close($ch);
	return $result;
}
?>