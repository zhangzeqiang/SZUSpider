<?php
if ( !defined('ROOT_PATH') ) {
	define("ROOT_PATH", substr(dirname(__FILE__), 0, -7) );
}
require_once(ROOT_PATH."\\Spider\\include\\function.php");
require_once(ROOT_PATH."\\Spider\\include\\inc.php");

/**
 * Get all the lesson pages in a term.
 * @param $term_no represent the term number (with lesson.)
 * @return true;
 */
function get_term_class_pages($term_no){

	//require_once(dirname(__FILE__)."\inc.php");
	set_time_limit(0);									//����ʱ���޳�ʱֹͣ
	get_college_page($term_no);							//ץȡѧԺ����ҳ��
	foreach($college_list as $college)
	{
		$filepl = "college_%s/%s.txt";
		$file = sprintf($filepl, $term_no, $college);
		$file_name = "resource/".$file;
		$output = file_get_contents($file_name);		//��ȡѧԺ����ҳ��Դ��

		$mode = array(
			FIRST => "tr",
			SECOND => "td",
			LAST => ""
		);
		$list = get_attr_lists($output, $mode);			//����ѧԺ����ҳ��Դ����ȡ����������
		$col_count = count($list[0]);
		$row_count = count($list);
		//echo $row_count." ".$col_count;
		$row = 0;

		for ($row=0;$row<$row_count;$row++){		//����
			
			$col = 0;
			if ($row == 0 || $row == ($row_count - 1)){
				continue;
			}
			for ($col=0;$col<$col_count;$col++){	//����
				
				if($col == lesson_no){
					//echo $list[$row][$col]." ";
					get_class_page($term_no, $list[$row][$col]);	//����ѧ�ںţ��γ̺Ż�ȡ�γ�ҳ��
					break;
				} 

			}

		}
	}
	return true;
}
/**
 * Get attribute with array mode(3 param=FIRST,SECOND,LAST).
 * @param String $string represent the string you want to split.
 * @param array @mode represent the mode used to split.
 * @notice:mode is a array{FIRST,SECOND,LAST}
 * @example:array{FIRST=>"tr", SECOND=>"td", LAST=>""} that represent to get attr from a table type.
 */
function get_attr_lists($string, array $mode){
	
	//$lists;
	$textpl = "<%s[^>]*>";
	$tr_mode = sprintf($textpl, $mode[FIRST]);
	$td_mode = sprintf($textpl, $mode[SECOND]);
	$other_mode = sprintf($textpl, $mode[LAST]);

	$tr_list = split($tr_mode, $string);
	$i = 0;
	$count_tr = count($tr_list);
	
	if ($tr_mode == "" || $td_mode == "")
		return false;
	foreach($tr_list as $tr){

		$i++;
		//if ($i != 1 && $i != $count_tr){		//�ָ����,���ﲻ�ܼӣ����������һЩ��¼
			$td_list = split($td_mode, $tr);
			$j = 0;
			
			$count_td = count($td_list);
			foreach($td_list as $td){

				$j++;
				if ($j != 1 && $j != $count_td){	//�ָ����
				
					$attr_list = split($other_mode, $td);

					foreach($attr_list as $attr){		//��ȡ����
						if (trim($attr) != ""){
							//echo $attr." ";
							
							$list[$i-2][$j-2] = trim($attr);
							//echo $list[$i-2][$j-2]." ";
						}
					}


				}

			}
		//}

	}
	return $list;
}
/**
 * get the colleges page in a term.
 * @param int term_no that you want to get the colleges pages. 
 * @return bool true;
 */
function get_college_page($term_no){
	//require_once('function.php');
	//require_once(dirname(__FILE__)."\inc.php");
	//$term_no = "20111";
	$url = "http://192.168.2.229/newkc/akcjj0.asp?xqh=".$term_no;	
	curl_post($url);		//����ѧ��
	$url = "http://192.168.2.229/newkc/akechengdw.asp";
	curl_post($url);		//ѧԺѡ��ҳ��
	$url = "http://192.168.2.229/newkc/kccx.asp?flag=kkdw";
	//print_r($college_list);
	//exit;
	foreach($college_list as $college)
	{
		$post = array(
		'bh' => $college,
		'SUBMIT' => "��ѯ"
		);
		$output = curl_post($url, $post);		//����ѧԺ
		save_resource($college.".txt", $output, "college_".$term_no);
	}
	return true;
}
/**
 * get the class page with lesson number and term number.
 * @param int term_no that you want to get the colleges pages. 
 * @param int lesson_no that you want to get the colleges pages.
 * @return bool true;
 * tip:the lesson_no and term_no is necessary.
 */
function get_class_page($term_no, $lesson_no){
	//require_once('function.php');
	/*$term_no = "20122";
	$class_no = "2112000102";*/
	$output = curl_class_page($term_no, $lesson_no);		//���ʿγ�
	save_resource($lesson_no.".txt", $output, "class_".$term_no);
	return true;
}
?>