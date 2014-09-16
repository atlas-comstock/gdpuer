<?php
header("content-Type: text/html; charset=utf-8");
include("db.php");


$xh=$_GET['xh'];
$pw=$_GET['pw'];
$api_url='http://zlgc.gdpu.edu.cn/chengji/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=2';
$str=file_get_contents($api_url);
if(strstr($str,"<script>")){
echo "请确认【账号】或【密码】是否正确&&发送 成绩#学号#密码 即可查询成绩";
return;
}


{//输出名字
$sql_name = "SELECT * FROM `jwc_personinfo` WHERE `xh` = '{$xh}' LIMIT 1 ";
$query_char=mysql_query("SET NAMES UTF8");
$query_name=mysql_query($sql_name,$link) or die(mysql_error());
$name_ret=mysql_fetch_row($query_name);
$name=$name_ret[2];
$xb=$name_ret[4];
if($xb=='男'){$ch='同学';}
if($xb=='女'){$ch='同学';}
}


{//搜索数据库是否已经有数据
$sql_check = "SELECT * FROM `jwc_chengji` WHERE `xh` = '{$xh}' LIMIT 1 ";
$query_char=mysql_query("SET NAMES UTF8");
$query_check=mysql_query($sql_check,$link) or die(mysql_error());
$check_ret=mysql_fetch_row($query_check);
}



$jieguo=po_chengji($str,$xh,$name,$ch,$link);
$title="{$name} {$ch}欢迎使用&【广药学生成绩查询】";
echo $title."&&".$jieguo;




function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}

function po_chengji($str,$xh,$name,$ch,$link) 
	{    
		$zxfxj=get_utf8_string("%实得总学分小计:");
		$pjxfjd=get_utf8_string("%平均学分绩点:");
		$zxfjd=get_utf8_string("%总学分绩点");

		$str=str_replace("</font></span></td>  	</tr>","%",$str);
		$str=str_replace("</td>  	</tr>","&】",$str);
		$str=str_replace("<tr>  		<td>","【",$str);
		$str=str_replace('<tr bgcolor="#C1D8F0">  		<td>',"【",$str);
		$str=str_replace('<tr bgcolor="#EEF3F9">  		<td>',"【",$str);
		$str=str_replace('<span id="Szxf"><font color="MediumBlue">',$zxfxj,$str);
		$str=str_replace('<span id="pjxfjd">',$pjxfjd,$str);
		$str=str_replace('<span id="zxfjd"><font color="DarkRed">',$zxfjd,$str);
		$str=str_replace('<span id="yhzxf"><font color="DarkRed">',"%",$str);
		$str=str_replace('</font></span>',"%】",$str);
		$str=str_replace("<td>&nbsp;</td>","",$str);
		$str=str_replace("<td>"," ",$str);
		$str=str_replace("<tr>","",$str);
		$str=str_replace("</tr>"," ",$str);
		$str=strip_tags($str);

		
		$str=explode("%",$str);
		$str2=explode("【",$str[0]);
		$string='';
		foreach($str2 as $key=>$a){
			if($key>=2){
			$a=str_replace("&】","&&",$a);
			$a=str_replace("    																			  																				","",$a);
			$string=$string.$a;
			}
	
		}
		$ret=$string."&".$str[4]."&".$str[6]."&".$str[8];
		return $ret;
	}
//print_r($str);
?>