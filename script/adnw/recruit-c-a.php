<?php
$clickurl=$_REQUEST["clickurl"];
$mediaid=$_REQUEST["mediaid"];
$bannerid=$_REQUEST["bannerid"];
$url="http://cdn.c-team.jp/".$bannerid."_js/".$mediaid."/".$bannerid.".html";
ini_set('user_agent', $_SERVER['HTTP_USER_AGENT']);
$tmp_html=file_get_contents($url);
$ad_html=str_replace("_blank\" href=\"","_blank\" href=\"".$clickurl,$tmp_html);
echo $ad_html;
