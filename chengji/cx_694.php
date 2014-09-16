<?php 
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>学生选修查询系统 By Yanson From OUR Studio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="OUR Studio, 学生选修查询系统">
  <meta name="author" content="YANSON">

</head>

<body>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fc8c6b4ac78fb9378987b263d5babdb1a' type='text/javascript'%3E%3C/script%3E"));
</script>


<form class="form-inline" action="" method="post">
	<label  for="xh">学号</label>
	<input id="xh" name="xh" type="text" />


	<label  for="pw">密码</label>
    <input id="pw" name="pw" type="text" />

	<button   onkeydown='if(event.keyCode==13){gosubmit();}' type="submit">查询</button>

			</form>



				<p>
					查询人信息
				</p>
  <p><small><?php echo get_personinfo_xuanxiu($_REQUEST['xh'],$_REQUEST['pw']);?></small></p>
	
		<?php echo get_xuanxiu($_REQUEST['xh'],$_REQUEST['pw']);?>
	

</body>
</html>


		

<?php
function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}

function get_personinfo_xuanxiu($xh,$pw) 
	{    	  
		$api_url='http://zlgc.gdpu.edu.cn/chengji/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=4';
		$ret_personinfo=file_get_contents($api_url);
		return $ret_personinfo;
		
	}
	
function get_chengji($xh,$pw) 
	{    	  
		$api_url='http://zlgc.gdpu.edu.cn/chengji/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=2';
		$ret_personinfo=file_get_contents($api_url);
		return $ret_personinfo;
		
	}
	
function get_xuanxiu($xh,$pw) 
	{    	  
		$api_url='http://zlgc.gdpu.edu.cn/chengji/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=3';
		$ret_personinfo=file_get_contents($api_url);
		return $ret_personinfo;
		
	}

function po_xuanxiu($xh,$pw) 
	{    	  
		$per_info_url='http://ours.123nat.com:59832/jwc/wx.xuanxiu.api.php?xh='.$xh.'&pw='.$pw;
		$ret_personinfo=file_get_contents($per_info_url);
		return $ret_personinfo;
	}	

function po_chengji($xh,$pw) 
	{    	  
		$per_info_url='http://ours.123nat.com:59832/jwc/web.chengji.api.php?xh='.$xh.'&pw='.$pw;
		$ret_personinfo=file_get_contents($per_info_url);
		return $ret_personinfo;
	}	
?>