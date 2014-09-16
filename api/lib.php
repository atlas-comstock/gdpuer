<?php
 //include '../common/phpheader.php';
?>
<?php
//ini_set("display_errors", 0);
header("Content-type: text/html; charset=utf-8");


$xh=$_REQUEST['xh'];

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
			$str=preg_replace('/<td width="96" align="center">(.*?)<td width="96" align="center">/s', "\n\n借书时间：", $str);


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

		echo $ret;
		//print_r($a);







function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}


	
	
?>