<?php
//ƥ�䷽ʽһ
$p4='/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" \/>/';
preg_match($p4,$result,$arr);

//ƥ�䷽ʽ��
$p4='#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" \/>#';
preg_match($p4,$result,$arr);

//ƥ�䷽ʽ��
$p4='/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value=".*" \/>/';
preg_match($p4,$result,$arr);

//���ֵ��Ľ������ͬ
?>
