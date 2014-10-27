<?php
/** 
 * Send a POST requst using cURL 
 * @param string $url to request 
 * @param array $post values to send 
 * @param array $options for cURL 
 * @return string 
 */ 
function curl_post($url, array $post = NULL, array $options = array()) 
{ 
	$cookie_file = dirname(__FILE__)."/cookie.txt";
    $defaults = array( 
       // CURLOPT_POST => 1, 
        CURLOPT_URL => $url, 
        CURLOPT_HEADER => 0, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => true,			//true代表$result为返回源码，false返回码
       // CURLOPT_FORBID_REUSE => 1, 
      //  CURLOPT_TIMEOUT => 120,
		CURLOPT_SSL_VERIFYHOST => 1,
		CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_COOKIEJAR => $cookie_file,
		CURLOPT_COOKIEFILE => $cookie_file
    ); 
	if (count($post) > 0){
		$defaults[CURLOPT_POST] = 1;		//设置为post方式
        $defaults[CURLOPT_POSTFIELDS] = http_build_query($post);
	}
    $ch = curl_init(); 
    curl_setopt_array($ch, ($options+$defaults)); 
    if( !$result = curl_exec($ch) ) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch);
	return $result;
}
/**
 * Get the lesson page code.
 * @param string $term_no represent the term number. 
 * @param string $class_no represent the class number.
 * @param string $ip to represent the Forged ip.
 * @return array resource;
 */
function curl_class_page($term_no="", $class_no="", $ip="192.68.177.1"){
	$url = 'http://192.168.2.229/newkc/djbprint.aspx?xqh=%s&ykch=%s';
	$url = sprintf($url, $term_no, $class_no);
	//echo $url;
	//$sendip="192.68.177.1";
	$ch = curl_init(); //初始化
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  
	curl_setopt($ch, CURLOPT_URL, $url); 
	//curl_setopt($ch, CURLOPT_REFERER,"http://192.168.2.229/newkc/djbprint.aspx?xqh=20122&ykch=2112000102"); //伪造来路页面
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0");  
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //是否显示内容
	$result=curl_exec($ch); //执行
	$info = curl_getinfo($ch); 
	curl_close($ch); //返回关闭
	return $result;
}
/**
 * Save the resource in file.
 * @param string $file_name to save as file name. 
 * @param $result is the resource handle 
 * @return bool true;
 */
function save_resource($file_name="unknown.txt", $result, $dir_name="resource"){
	//if resource/college exits
	if (!is_dir("resource")){
		mkdir("resource");
	}
	if ($dir_name != "resource"){
		if (!is_dir("resource/".$dir_name)){
			mkdir("resource/".$dir_name);
		}
	}else{
		$dir_name="../resource";
	}
	$fh = fopen("resource/".$dir_name."/".$file_name, 'w') ;  
	fwrite($fh, $result) ;  
	fclose($fh) ; 
	return true;
}
?>