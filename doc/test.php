<?php
/*
$post_data = array (
    "__VIEWSTATE" => "/wEPDwUJMzUxMzEwNDU0DxYEHgNYcWgFBTIwMTMyHgNLY2gFCjIwMDAwNDAwMDEWAgIDD2QWAgIDD2QWBAIBDxcDBQRCQVNFZAUDY3NzZQUPUmVwb3J0Vmlld1N0YXRlFwYFCkRlc2lnbk1vZGVoBRJQYWdlUmVxdWVzdENvbnRleHQXAwUKUGFnZU51bWJlcgIBBRVJc0xhc3RQYWdlTnVtYmVyS25vd25nBQ5MYXN0UGFnZU51bWJlcgIDBQdGYWN0b3J5BZYBQ3J5c3RhbERlY2lzaW9ucy5SZXBvcnRTb3VyY2UuUmVwb3J0U291cmNlRmFjdG9yeSxDcnlzdGFsRGVjaXNpb25zLlJlcG9ydFNvdXJjZSwgVmVyc2lvbj0xMC4yLjM2MDAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj02OTJmYmVhNTUyMWUxMzA0BQdSZWZyZXNoaAUJUmVwb3J0VVJJZQUJUnB0U291cmNlBTdDcnlzdGFsRGVjaXNpb25zLlJlcG9ydFNvdXJjZS5Ob25IVFRQQ2FjaGVkUmVwb3J0U291cmNlFgICAg8XAQUCYnMCgeT//w8WAgILDxAPFgIeC18hRGF0YUJvdW5kZ2QQFQEJ5Li75oql6KGoFQGoA0FBRUFBQUQvLy8vL0FRQUFBQUFBQUFBRUFRQUFBQnhUZVhOMFpXMHVRMjlzYkdWamRHbHZibk11U0dGemFIUmhZbXhsQndBQUFBcE1iMkZrUm1GamRHOXlCMVpsY25OcGIyNElRMjl0Y0dGeVpYSVFTR0Z6YUVOdlpHVlFjbTkyYVdSbGNnaElZWE5vVTJsNlpRUkxaWGx6QmxaaGJIVmxjd0FBQXdNQUJRVUxDQnhUZVhOMFpXMHVRMjlzYkdWamRHbHZibk11U1VOdmJYQmhjbVZ5SkZONWMzUmxiUzVEYjJ4c1pXTjBhVzl1Y3k1SlNHRnphRU52WkdWUWNtOTJhV1JsY2dqc1VUZy9Bd0FBQUFvS0N3QUFBQWtDQUFBQUNRTUFBQUFRQWdBQUFBTUFBQUFHQkFBQUFBNU1ZWE4wVUdGblpVNTFiV0psY2dZRkFBQUFGVWx6VEdGemRGQmhaMlZPZFcxaVpYSkxibTkzYmdZR0FBQUFDbEJoWjJWT2RXMWlaWElRQXdBQUFBTUFBQUFJQ0FNQUFBQUlBUUVJQ0FFQUFBQUwUKwMBZxYBZmQCAw8XAGQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgsFEmNydkRqYiRjdGwwMiRjdGwwMAUSY3J2RGpiJGN0bDAyJGN0bDAxBRJjcnZEamIkY3RsMDIkY3RsMDIFEmNydkRqYiRjdGwwMiRjdGwwMwUSY3J2RGpiJGN0bDAyJGN0bDA0BRJjcnZEamIkY3RsMDIkY3RsMDUFEmNydkRqYiRjdGwwMiRjdGwwNgUSY3J2RGpiJGN0bDAyJGN0bDA3BRJjcnZEamIkY3RsMDIkY3RsMTAFEmNydkRqYiRjdGwwMiRjdGwxMgUSY3J2RGpiJGN0bDAyJGN0bDE0ZpgbC+R/IJjVL/5EwVmhUHmBT7o=",
    "__EVENTVALIDATION" => "/wEWBwKXlbnNBQKSoqqWDwL4h7OTCwL4h8eTCwL4h8OTCwL4h7+TCwL4h7uTC7wRpKMqtyLBZFlvT+d2EZHnnK91",
    'crvDjb$ctl02$ctl06.x' => "14",
	'crvDjb$ctl02$ctl06.y' => "11");
$url = 'http://192.168.2.229/newkc/djbprint.aspx?xqh=20132&ykch=2000160002';
$sendip="192.68.177.1";
$year="20132";
$kechenid="0300030001";
$ch = curl_init(); //初始化
curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$sendip, 'CLIENT-IP:'.$sendip));  
curl_setopt($ch, CURLOPT_URL, $url); 
//curl_setopt($ch, CURLOPT_REFERER,"http://192.168.2.229/newkc/djbprint.aspx?xqh=20122&ykch=2112000102"); //伪造来路页面
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0"); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //是否显示内容
$a=curl_exec($ch); //执行
//$info = curl_getinfo($ch); 
//print_r($info);
//$p4='#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" />#s';
//preg_match($p4,$a,$arr);
echo $a;
//print_r($arr);
curl_close($ch); //返回关闭
*/
$file = "Spider/resource/class_20132/0100170004.txt";
$result = file_get_contents($file);

$p4='/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" \/>/';
preg_match($p4,$result,$arr);

echo $arr[1];
//print_r($arr);
?>