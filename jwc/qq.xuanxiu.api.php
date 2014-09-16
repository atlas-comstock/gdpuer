<?php 
header("Content-type: text/html; charset=utf-8");
include("db.php");


$xh=$_GET['xh'];
$pw=$_GET['pw'];
$api_url='http://zlgc.gdpu.edu.cn/chengji/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=3';
$str=file_get_contents($api_url);
if(strstr($str,"<script>")){
echo "请确认【账号】或【密码】是否正确";
return;
}

/*
$str=<<<eot
﻿<table cellspacing="0" cellpadding="3" rules="rows" bordercolor="#ADCEEF" border="1" id="dgXKXX" width="100%">  	<tr bgcolor="#C1D8F0">  		<td>学年</td><td>学期</td><td>选课课号</td><td>课程代码</td><td>课程名称</td><td>学分</td><td>学科类别</td><td>起止周</td><td>上课时间</td><td>上课地点</td><td>教师姓名</td><td>预订教材</td><td>志愿</td>  	</tr><tr>  		<td>2010-2011</td><td>2</td><td>62</td><td>G0604001</td><td>中医药保健与亚健康防治</td><td>2</td><td>医药生物特色类</td><td>03-11</td><td>周6第1,2,3,4节</td><td>A1-201</td><td>胡旭光,戴王强</td><td>0</td><td>1</td>  	</tr><tr bgcolor="#EEF3F9">  		<td>2010-2011</td><td>2</td><td>138</td><td>G0314003</td><td>医药大学生职业发展与规划</td><td>2</td><td>人文社科类</td><td>03-14</td><td>周3第9,10,11节</td><td>A2-324</td><td>许蕾,林琳</td><td>0</td><td>1</td>  	</tr><tr>  		<td>2011-2012</td><td>1</td><td>51</td><td>G0604001</td><td>中医药保健与亚健康防治</td><td>2</td><td>医药生物特色类</td><td>03-11</td><td>周6第1,2,3,4节</td><td>A2-222</td><td>胡旭光,戴王强</td><td>0</td><td>1</td>  	</tr><tr bgcolor="#EEF3F9">  		<td>2011-2012</td><td>1</td><td>57</td><td>G0902001</td><td>大学生学术（毕业）论文写作指导</td><td>1.50</td><td>人文社科类</td><td>03-11</td><td>周4第9,10,11节</td><td>A2-225</td><td>张玲</td><td>0</td><td>1</td>  	</tr><tr>  		<td>2011-2012</td><td>1</td><td>59</td><td>G0902007</td><td>灾难事故之逃生与急救</td><td>2</td><td>医药生物特色类</td><td>03-14</td><td>周2第9,10,11节</td><td>A2-227</td><td>王金全,王丁丁</td><td>0</td><td>1</td>  	</tr><tr bgcolor="#EEF3F9">  		<td>2011-2012</td><td>2</td><td>136</td><td>G2901011</td><td>中国民族音乐欣赏</td><td>2</td><td>非限定性公共艺术类</td><td>03-14</td><td>周2第9,10,11节</td><td>A1-301</td><td>曾嘉</td><td>0</td><td>1</td>  	</tr><tr>  		<td>2012-2013</td><td>1</td><td>60</td><td>G2901010</td><td>戏剧鉴赏</td><td>2</td><td>限定性公共艺术类</td><td>03-14</td><td>周1第9,10,11节</td><td>A1-201</td><td>潘华</td><td>0</td><td>1</td>  	</tr>  </table>
eot;
*/

{//输出名字
$sql_name = "SELECT * FROM `jwc_personinfo` WHERE `xh` = '{$xh}' LIMIT 1 ";
$query_char=mysql_query("SET NAMES UTF8");
$query_name=mysql_query($sql_name,$link) or die(mysql_error());
$name_ret=mysql_fetch_row($query_name);
$name=$name_ret[2];
$xb=$name_ret[4];
if($xb=='男'){$ch='湿胸';}
if($xb=='女'){$ch='湿姐';}
}


{//搜索数据库是否已经有数据
$sql_check = "SELECT * FROM `jwc_xuanxiu` WHERE `xh` = '{$xh}' LIMIT 1 ";
$query_char=mysql_query("SET NAMES UTF8");
$query_check=mysql_query($sql_check,$link) or die(mysql_error());
$check_ret=mysql_fetch_row($query_check);
}
if($check_ret[2]){
$title="{$name} {$ch}欢迎使用@【广药选修查询系统】";
echo $title."@@".$check_ret[2];
}else{
echo "亲爱的{$name} {$ch}@正在为你链接到JW处...@请重新发送@@选修#学号#密码@@来获得最新选修信息";



		$str=str_replace("<tr>  		<td>","【",$str);
		$str=str_replace('<tr bgcolor="#EEF3F9">  		<td>',"【",$str);
		$str=str_replace('</td>  	</tr>',"】",$str);
		$str=str_replace('</td><td>',"#",$str);
		$str=explode("【",$str);
		$string='';
		foreach($str as $key=>$a){
			if($key>=1){
			$b=explode("#",$a);
		//$a=str_replace("】","@",$a);
			$string .= "学年:".$b[0]."@".'学期:'.$b[1]."@".'课程名称:'.$b[4]."@".'学分:'.$b[5]."@".'起止周:'.$b[7]."@".'上课时间:'.$b[8]."@".'上课地点:'.$b[9]."@".'教师姓名:'.$b[10]."@@";
			}
	
		}

		$title="{$name} {$ch}欢迎使用@【广药学生选修查询】";
		$ret=$string;

	
		$sql_po_chengji = "REPLACE INTO `jwc_xuanxiu` (`xh`, `name`,`xuanxiu`, `time`) VALUES ('{$xh}','{$name}', '{$ret}', TIMESTAMP(10));";
        $query_char=mysql_query("SET NAMES UTF8");
        $query_po_chengji=mysql_query($sql_po_chengji,$link) or die(mysql_error());
}


function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}
function po_xuanxiu($xh,$ch,$name,$str,$link) 
	{   
		
		$str=str_replace("<tr>  		<td>","【",$str);
		$str=str_replace('<tr bgcolor="#EEF3F9">  		<td>',"【",$str);
		$str=str_replace('</td>  	</tr>',"】",$str);
		$str=str_replace('</td><td>',"#",$str);
		$str=explode("【",$str);
		$string='';
		foreach($str as $key=>$a){
			if($key>=1){
			$b=explode("#",$a);
		//$a=str_replace("】","@",$a);
			$string .= "学年:".$b[0]."@".'学期:'.$b[1]."@".'课程名称:'.$b[4]."@".'学分:'.$b[5]."@".'起止周:'.$b[7]."@".'上课时间:'.$b[8]."@".'上课地点:'.$b[9]."@".'教师姓名:'.$b[10]."@@";
			}
	
		}

		$title="{$name} {$ch}欢迎使用@【广药学生选修查询】";
		$ret=$title."@@".$string;

	
		$sql_po_chengji = "REPLACE INTO `jwc_xuanxiu` (`xh`, `name`,`xuanxiu`, `time`) VALUES ('{$xh}','{$name}', '{$ret}', TIMESTAMP(10));";
        $query_char=mysql_query("SET NAMES UTF8");
        $query_po_chengji=mysql_query($sql_po_chengji,$link) or die(mysql_error());
	}
	

?>