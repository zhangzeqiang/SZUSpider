<?php
//匹配方式一
$p4='/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" \/>/';
preg_match($p4,$result,$arr);

//匹配方式二
$p4='#<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="(.*)" \/>#';
preg_match($p4,$result,$arr);

//匹配方式三
$p4='/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value=".*" \/>/';
preg_match($p4,$result,$arr);

//三种到的结果都不同
?>
