<?php
header("content-Type: text/html; charset=utf-8");
class WebAPI{
	//学校新闻 
 	public function get_gdpu_news()    
	{    				
      	$url = 'http://av.jejeso.com/helper/api/news/news.php';		
		$result= file_get_contents($url);
		return $result;   
	}
	//就业信息 
	 public function get_gdpu_jobs()    
	{    				
		$url = 'http://av.jejeso.com/helper/api/jobs/jobs.php';		
		$result= file_get_contents($url);
		return $result;   
	}
	//图书信息  
 	public function get_lib_book($keyword)    
	{    		
		$url = 'http://av.jejeso.com/helper/api/book/book.php?keyword='.$keyword;		
		$result= file_get_contents($url);
		return $result;  
	}
    //还书信息 
  	public function get_lib_boorowbook($keyword)    
	{    		
		$url = 'http://av.jejeso.com/helper/api/lib.php?xh='.$keyword;		
		$result= file_get_contents($url);
		return $result;  
	}
	//勤管兼职  
 	public function get_gdpu_partime()    
	{    		
		$url = 'http://av.jejeso.com/helper/api/partime/partime.php';		
		$result= file_get_contents($url);
		return $result;  
	}
  	//查四六级  
 	public function get_ours_cet($zkzh,$xm)    
	{    				
        $url = 'http://av.jejeso.com/helper/api/ours/cet.php?zkzh='.$zkzh.'&xm='.$xm;		
		$result= file_get_contents($url);
		return $result;   
	}
  	 	public function get_cet($zkzh)    
	{    				
        $url = 'http://av.jejeso.com/helper/chengji/cet_wx.php?xh='.$zkzh;		
		$result= file_get_contents($url);
		return $result;   
	}

	public function enTozh($key){
		$url="http://brisk.eu.org/api/translate.php?from=en&to=zh-CN&text=".$key;
 
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$data=curl_exec($curl);
		 
		curl_close($curl);
		 
		$json=json_decode($data);
		return $json->{'res'}."\n";
	}
	//快递查询

	public function kuaidi($com,$no){
		$url = "http://av.jejeso.com/helper/api/kuaidi/get_kuaidi.php?com=".$com."&no=".$no;
		$result = file_get_contents($url);
		return $result;
	}
  
  	//周公解梦 
 	public function get_ours_dream($key)    
	{  
        $key=rawurlencode(mb_convert_encoding($key, 'gbk', 'utf-8'));
        $url = 'http://www.gpsso.com/WebService/Dream/Dream.asmx/SearchDreamInfo?Dream='.$key; 
		$string = file_get_contents($url);
		$xml = simplexml_load_string($string);
		return $xml->DREAM; 
	}
  	//今日彩票
 	public function get_ours_award()    
	{  
        $url = 'http://www.gpsso.com/webservice/caipiao/award.asmx/GetAward?'; 
		$string = file_get_contents($url);
		$xml = simplexml_load_string($string);
      	$result='['.$xml->SSQ.']'.'['.$xml->SD.']'.'['.$xml->QLC.']'.'['.$xml->DLT.']'.'['.$xml->PLS.']'.'['.$xml->PLW.']'.'['.$xml->QXC.']'.'['.$xml->SYXW.']'.'['.$xml->XSSC.']';
		return $result; 
	}
  	//身份证号
 	public function get_ours_idcard($no)    
	{  
        $url = 'http://www.gpsso.com/webservice/idcard/idcard.asmx/SearchIdCard?IdCard='.$no; 
		$string = file_get_contents($url);
		$xml = simplexml_load_string($string);
      	$result='性别:'.'['.$xml->SIX.']'."\n".'出生:'.'['.$xml->BIRTHDAY.']'."\n".'农历:'.'['.$xml->NONGLI.']'."\n\t".'['.$xml->WEEK.']'."\n".'地址:'.'['.$xml->ADDRESS.']';
		return $result; 
	}
  	//查手机号 
 	public function get_ours_mobile($number)    
	{    				
        $url = 'http://av.jejeso.com/helper/api/ours/mobile.php?number='.$number;		
		$result= file_get_contents($url);
		return $result;   
	}
 	 //发找找帮
 	public function send_ours_zzbon($text)    
	{     	
        $url = 'http://av.jejeso.com/helper/api/ours/zzbon.php?key='.$text.'&wx_token='.md5('zzbon');		
		$result= file_get_contents($url);
		return $result;   
	}
  	 //豆瓣听歌
 	public function get_song_random()    
	{	
        $url = 'http://av.jejeso.com/helper/api/song/random.php';		
		$result= file_get_contents($url);
		return $result;
    }
  	 //腾讯音乐
 	public function get_song_tencent($key)    
	{	
        $url = 'http://av.jejeso.com/helper/api/song/song.php?key='.$key;		
		$result= file_get_contents($url);
		return $result;
    }
	//优酷视频
 	public function get_video_youku($key)    
	{	
        $url = 'http://av.jejeso.com/helper/api/ours/youku.php?key='.$key;		
		$result= file_get_contents($url);
		return $result;
    }
	//表白
 	public function get_biaobai($key,$from)    
	{	
        $url = 'http://phpdo9.nat123.net:52182/helper/api/biaobai/biaobai.php?key='.$key.'&from='.$from;		
		$result= file_get_contents($url);
		return $result;
    }
    //公交线路
	public function get_bus($city,$no){
		$url =  'http://av.jejeso.com/helper/api/bus/get_bus.php?city='.$city.'&no='.$no;
		$result = file_get_contents($url);
		return $result;
	}
  	//解码字符
  	function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}

}
?>