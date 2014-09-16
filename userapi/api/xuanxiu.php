<?php
	$content = $this->message['content'];
	$from_user=$this->message['from'];
	$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
	$row_info = pdo_fetch($sql, array(':from_user' => $from_user));
	if($content=='退出' || $content=='取消'){
		$this->endContext();
		return $this->respText("你已经退出本模式");
	}
	
	
	if (empty($row_info['xh'])) {
	
	
			// 如果是按照规则触发到本模块, 那么先输出提示问题语句, 并启动上下文来锁定会话, 以保证下次回复依然执行到本模块
			$content=str_replace('＃','#',$content);
			$ret=explode('#',$content);
			$xh=$ret[1];
			$pw=$ret[2];
			if(($xh)&&($pw)){
				$api_url='http://ours.123nat.com:59832/api/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=3';
				//$api_url='http://ours.123nat.com:59832/api/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=2';
				$str=file_get_contents($api_url);
				if(strstr($str,"请确认用户名或密码是否正确")){
					return $this->respText("【账号】或【密码】错误");
				}
				$info=file_get_contents('http://ours.123nat.com:59832/api/chengji/jwc_personinfo.php?xh='.$xh.'&pw='.$pw);
				$info=json_decode($info,true);
				$name=$info['xm'];
				$string=po_xuanxiu($str);
				$title="{$name} {$ch}欢迎使用\n【广药学生成绩查询】";
				return $this->respText($title."\n\n".$string);
				
			}elseif((!$xh)||(!$pw)){
				$content="请检查【格式】是否正确\n\n选修#学号#密码\n\n【绑定之后】\n直接回复【选修】\n\n想直接快速查询请先回复\n\n绑定\n\n进行【绑定】";
			}else{
				$content="请检查【格式】是否正确\n\n选修#学号#密码\n\n【绑定之后】\n直接回复【选修】\n\n想直接快速查询请先回复\n\n绑定\n\n进行【绑定】";
			}
	
		return $this->respText($content);

	}else{
	
	$xh=$row_info['xh'];
	$pw=$row_info['jwcpwd'];
	
	$api_url='http://ours.123nat.com:59832/api/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=3';
	//$api_url='http://10.50.25.9/api/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=3';
	$str=file_get_contents($api_url);

	$tj='http://pingtcss.qq.com/pingd?dm=yanson.duapp.com&pvi='.rand(1,9999999999).'&si=s'.rand(1,9999999999).'&url=/jwc/new.wx.chengji.api.php&arg=&ty=&rdm=&rurl=&rarg=&adt=&r2=31957828&r3=-1&r4=1&fl=12.0&scr=1280x960&scl=24-bit&lg=zh-cn&jv=1&tz=-8&ct=&ext=adid=&pf=&random=1397458406451';
	file_get_contents($tj);
		
	if(strstr($str,"请确认用户名或密码是否正确")){
		return $this->respText("【账号】或【密码】错误");
	}
	
	{//输出名字

	$name=$row_info['realname'];
	$xb=$row_info['xb'];
	if($xb=='男'){$ch='同学';}
	if($xb=='女'){$ch='同学';}
	}

	$string=po_xuanxiu($str);
	$title="{$name} {$ch}欢迎使用\n【广药学生选修查询】";
	return $this->respText($title."\n\n".$string);

	}
	
	
	
	

	function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}
	
	function po_chengji_new($str){
		preg_match('/<table cellspacing=\"0\" cellpadding=\"3\" rules=\"rows\" bordercolor=\"#ADCEEF\" border=\"1\" id=\"DataGrid1\" width=\"100%\">(?P<info>.+?)<\/table>/s', $str, $results);
		$array=get_td_array($results['info']);
		array_splice($array, 0, 1);

		/*
		 Array
				(
					[0] => 学年
					[1] => 学期
					[2] => 课程名称
					[3] => 课程类型
					[4] => 任课教师
					[5] => 考核方式
					[6] => 总评成绩
					[7] => 补考成绩
					[8] => 重修成绩
					[9] => 重修成绩2
					[10] => 重修成绩3
					[11] => 绩点
					[12] => 应得学分
				)
		 */
		for ($i = 0; $i < count($array); $i++)
		{
			 for ($j = 0; $j < count($array[$i]); $j++)
			 {
				if ($array[$i][$j] == "&nbsp;")
				{
					$array[$i][$j] = "";
				}
				$array[$i][$j]=trim($array[$i][$j]);
			}
		}
		//print_r($array);

		//取出最后一学期的成绩
		$cj='';
		$str='';
		$xn="";
		for ($i = count($array)-1; $i >= 0; $i--)
		{
			$cj='';
			$cj="课程名称：".$array[$i][2]."\n"."课程类型：".$array[$i][3]."\n"."任课教师：".$array[$i][4]."\n"."总评成绩：".$array[$i][6]."\n";
			//判断最终成绩
			
			if(!empty($array[$i][7]))
			{
				$cj.="补考成绩：".$array[$i][7]."\n";
			}
			elseif (!empty($array[$i][8]))
			{
				$cj.="重修成绩：".$array[$i][8]."\n";
			}
			elseif (!empty($array[$i][9]))
			{
				$cj.="重修成绩2：".$array[$i][9]."\n";
			}
			elseif (!empty($array[$i][10]))
			{
				$cj.="重修成绩3：".$array[$i][10]."\n";
			}

			$cj.="绩点：".$array[$i][11]."\n"."应得学分：".$array[$i][11]."\n----------\n";
			
			//学年不一样时结束循环
			if ($array[$i][0] != $array[$i-1][0])
			{
			break;
				
			}
			if($array[$i-1][1] == '1' && $array[$i][1] != $array[$i-1][1])
			{
			 $cj.=$array[$i-1][0]."学年\n第 ".$array[$i-1][1]." 学期\n----------\n";
			}
			
			$str.=$cj;
			

		}
		$ret=$array[count($array)-1][0]."学年\n第 ".$array[count($array)-1][1]." 学期\n----------\n".$str;

		return $ret;
	}

	function get_td_array($table)

	{
	  $table = preg_replace("'<table[^>]*?>'si","",$table);
	  $table = preg_replace("'<tr[^>]*?>'si","",$table);
	  $table = preg_replace("'<td[^>]*?>'si","",$table);
	  $table = str_replace("</tr>","{tr}",$table);
	  $table = str_replace("</td>","{td}",$table);
	  //去掉 HTML 标记 
	  $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
	  //去掉空白字符  
	  $table = preg_replace("'([rn])[s]+'","",$table);
	  $table = str_replace(" ","",$table);
	  $table = str_replace(" ","",$table);
	  $table = explode('{tr}', $table);
	  array_pop($table);
	  foreach ($table as $key=>$tr)
	  {
		  $td = explode('{td}', $tr);
		  array_pop($td);
		  $td_array[] = $td;
	  }
	   return $td_array;
	}	
	
	function po_xuanxiu($str) 
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
		//$a=str_replace("】","\n",$a);
			$string .= "学年:".$b[0]."\n".'学期:'.$b[1]."\n".'课程名称:'.$b[4]."\n".'学分:'.$b[5]."\n".'起止周:'.$b[7]."\n".'上课时间:'.$b[8]."\n".'上课地点:'.$b[9]."\n".'教师姓名:'.$b[10]."\n\n";
			}
	
		}
		return $string;
	}
		?>