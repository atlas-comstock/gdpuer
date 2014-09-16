<?php
include("wechatext.class.php");
global $_W;
$content = $this->message['content'];
$from_user=$this->message['from'];

	$sql = "SELECT * FROM " . tablename('stubind_reply') . " WHERE `weid`=:weid LIMIT 1";
	$row = pdo_fetch($sql, array(':weid' => $_W['weid']));

		//自动获取用户信息
		$options = array(
		'account'=> $row['account'] ,
		'password'=>$row['password'],
		'datapath'=>$_W['attachurl'].'cookie_',
		); 
		$wechat = new Wechatext($options);
		
		$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
		$row_info = pdo_fetch($sql, array(':from_user' => $from_user));

$matchs = array();



if (empty($row_info['xh'])) {
$ret = preg_match('/还书(?P<xh>[0-9]{10})$/i', $this->message['content'], $matchs);
$xh=$matchs['xh'];
}else{
$xh=$row_info['xh'];
}

if(!$xh){
return $this->respText("请输入合适的格式, 还书+学号, 例如: \n\n还书1207511199");
}
		$geturl_book = "http://opac.lib.gdpu.edu.cn/cgi-win/service.exe";
		$post_fields = 'cardno='.$xh.'&pass=666666&query=query';
		$header[]='Content-Type:application/x-www-form-urlencoded';

		$ch = curl_init($geturl_book);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		$string = curl_exec($ch);

		$string=get_utf8_string($string);//图书馆网站编码GB2312都要转UTF-8方便操作
		preg_match('/<table width="98%" align="center" bordercolorlight="#336699" bordercolordark="#eeeeee" border="1" cellspacing="0" cellpadding="1">(?P<info>.+?)<\/table>/s', $string, $results);
	
	  	$table = str_replace("<tr>","】",$results['info']);
		//echo $table;
		$str=preg_replace('/&nbsp;<td><a href="(.*?)">/s', "\n\n题名／著者：", $table);
		$str=str_replace('<td width="36" align="center">续满',"<td><input type=\"checkbox\" value=\"11\">",$str);
		$str=str_replace('<td width="96" align="center">&nbsp;',"a最迟应还期：",$str);
		$str=str_replace('<td width="36" align="center">',"",$str);
		$str=str_replace('</a><td width="72" align="center">&nbsp;<td width="96" align="center">',"\n\n图书类型：",$str);
		//echo $str;
		$str=preg_replace('/<input (.*?)a最迟应还期：/s', "最迟应还期：", $str);
		$str=preg_replace('/<td width="96" align="center">[a-zA-Z+](.*?)<td width="96" align="center">/s', "\n\n登录号：$0", $str);
		$str=str_replace('登录号：<td width="96" align="center">',"登录号：",$str);
		$str=str_replace('<td width="96" align="center">',"\n\n借期：",$str);
	
		$str = preg_replace("'<[/!]*?[^<>]*?>'si","",$str);
			//echo $str;
		 $table = explode('】', $str);
		 
		
			
			$ret='';
			foreach($table as $k=>$v)
			{
				if($k>0){
				$ret.=$v;
				}
			}
		
		if($ret=='')
		{
			$ret="亲，你木有借书呀！";
		}
		$err='亲，图书馆网站的功能出现问题了，无法帮你查询，请反馈信息给图书馆信息技术部~';
		
		if (!empty($row_info['fakeid'])) {

				if ($wechat->checkValid()) {
					$fakeid=$row_info['fakeid'];//fakeid
					$send=$wechat->send($fakeid,$ret);
					$se=json_decode($send,true);
					if($se['base_resp']['err_msg']=='ok'){
						return $this->respText($xh.'请稍等..');
					}else{
						return $this->respText($err);
					}
		}
}
		
		//return $this->respText($ret);
		
		
		
		
		
		/*
		$string=get_utf8_string($string);//图书馆网站编码GB2312都要转UTF-8方便操作
		$string=str_replace('<td width="36" align="center">续满</td>',"<input type="checkbox" value='123'>",$string);
		preg_match('/<input type="checkbox" (.*?)>(?P<info>.+?)<\/tr>/s', $string, $results);
		
		//echo $results['info'];
		if(isset($results['info']))
		{
			$str=str_replace('</table>',"】",$results['info']);
			$str=preg_replace('/&nbsp;<td><a href="(.*?)">/s', "\n\n题名／著者：", $str);
			$str=str_replace('<td width="96" align="center">&nbsp;',"\n\n最迟应还期：",$str);
			$str=str_replace('<td width="36" align="center">',"",$str);
			$str=str_replace('</a><td width="72" align="center">&nbsp;<td width="96" align="center">',"\n\n图书类型：",$str);
			$str=preg_replace('/<tr><input (.*?)最迟应还期：/s', "\n\n\n【最迟应还期：", $str);
			$str=preg_replace('/<td width="96" align="center">(.*?)<td width="96" align="center">/s', "\n\n借期：", $str);

			$str=explode('】',$str);
			$str=$str['0'];//去掉后面FOOTER
			$str=explode('【',$str);//分割每本书

			//$a=array('title'=>'借书情况');
			foreach($str as $b)
			{
			//array_push($a,$b);
			$ret.=$b;
			}
		}
		else
		{
		$ret="亲，你木有借书呀！";
		}
		
		return $this->respText($ret);
		



*/



function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}


	
	
?>