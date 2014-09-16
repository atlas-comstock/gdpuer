<?php ini_set("display_errors", 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>OUR Studio, 学生选修查询系统</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="OUR Studio, 学生成绩查询系统">
  <meta name="author" content="YANSON">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/fa.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<!--script type="text/javascript" src="js/alert.js"></script>
	<script type="text/javascript" src="js/jquery.blockUI.js"></script-->
	<script type="text/javascript" src="js/fancybox/lib/jquery-1.9.0.min.js"></script>
	<script type="text/javascript" src="js/fancybox/source/jquery.fancybox.css?v=2.1.4"></script>
	<script type="text/javascript" src="js/fancybox/source/jquery.fancybox.js?v=2.1.4"></script>

	
</head>

<body>
<div class="container-fluid">

	<div class="row-fluid">
		<div class="span12">
		<!--div id="messageBox" style="width=100%; height:30px;">
			<div id="message" style="dispaly:none;"></div>
			</div-->
			<div class="page-header">
				<h1>
					OUR Studio <span>学生选修查询系统</span>
				</h1>
				<span class="label"  class="fancybox" >开发者</span><h7>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yanson&nbsp;&nbsp;Anywill</h7>
			</div>
			<div class="alert alert-error">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				<h1>
					提示!
				</h1> <h4><strong>警告!</strong> 本系统纯属为方便广药学生而制作，非官方，低调传播，不然...你懂的</h4>
			</div>
		</div>
	</div>
	<div class="row-fluid">
	<div class="span4">
		
		</div>
		<div class="span4">
			
			<!--form class="form-horizontal" action="" method="post"-->
			<form class="form-inline" action="xuanxiu.php" method="post">
				<div class="control-group">
					<label class="control-label" for="xh">学号</label>
					<div class="controls">
						<input id="xh" name="xh" type="text" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pw">密码</label>
					<div class="controls">
						<input id="pw" name="pw" type="password" />
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-primary btn-large"  onkeydown='if(event.keyCode==13){gosubmit();}' type="submit">查询</button>
					</div>
				</div>
			</form>
			
		</div>
		
		<div class="span4">
		<blockquote>
				<p>
					查询人信息
				</p> <p><small></br><?php echo write_personinfo($_POST['xh'],$_POST['pw']);?></small></p>
			</blockquote>
		</div>				
  </div>
</div>
</body>
</html>


		

<?php header("Content-type: text/html; charset=utf-8");
include("dom.php");
include("db.config.php");


$xh = $_POST['xh'];
$pw = $_POST['pw'];

if(isset($_POST['xh'])&&isset($_POST['pw'])){
//$ret=write_personinfo($xh,$pw);
//提交账号和密码，身份模拟登陆
$info=write_personinfo($_POST['xh'],$_POST['pw']);
if($info=="获取失败..."){
echo '<script>$.fancybox({overlayShow : true,overlayOpacity:0.5,overlayColor : "#000000",autoHeight: true,content:"请确认用户名或密码是否正确"}); setTimeout("$.fancybox.close()",4000); </script><script>ShowMessage("获取失败...请检查你的用户名或密码是否正确","#FFCCCC")</script>';
}
$post_fields 	= '__VIEWSTATE=dDwtNjg3Njk1NzQ3O3Q8O2w8aTwxPjs%2BO2w8dDw7bDxpPDg%2BO2k8MTM%2BO2k8MTU%2BOz47bDx0PHA8O3A8bDxvbmNsaWNrOz47bDx3aW5kb3cuY2xvc2UoKVw7Oz4%2BPjs7Pjt0PHA8bDxWaXNpYmxlOz47bDxvPGY%2BOz4%2BOzs%2BO3Q8cDxsPFZpc2libGU7PjtsPG88Zj47Pj47Oz47Pj47Pj47bDxpbWdETDtpbWdUQzs%2BPvpW9bNHRO98aj%2BzEmn77FHqeOjK&tbYHM='.$xh.'&tbPSW='.$pw.'&ddlSF=%D1%A7%C9%FA&imgDL.x=28&imgDL.y=19';
$submit_url 	= 'http://10.50.17.2/default3.aspx';//提价页面

$ch = curl_init($submit_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HEADER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
$contents = curl_exec($ch);

preg_match('/Set-Cookie: (.*);/i', $contents, $results);
$cookie = $results[1];
curl_close($ch);//提取成功之后的ASP.NetSessionId的cookies

//读取操作页面

/*
$geturl_xsxx = 'http://10.50.17.2/xsxx.aspx?xh='.$xh;//个人信息页面
$geturl_xscj = 'http://10.50.17.2/xscj.aspx?xh='.$xh;//学生成绩页面
           */

$geturl_xx = 'http://10.50.17.2/ryxk.aspx?xh='.$xh;



$header[]='Cookie:'.$cookie;

$ch = curl_init($geturl_xx);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
curl_setopt($ch, CURLOPT_HEADER, 0);

 $string = curl_exec($ch);//输出内容

$string=get_utf8_string($string);//转成utf8

    $html = new simple_html_dom();
    $html->load($string);
    $table=$html->find('table[id=dgXKXX]',0);
    $text=$table->outertext;
	echo $text;
	
	//preg_match('/<tr>(.*)<\/tr>/', $text, $results);
	//print_r($results);
	//$ret=str_replace("</td><td>","|",$results);
	//print_r($ret);
	//$ret=explode("|",$ret[1]);
	//print_r($ret);
	//var_dump($ret);


}




function get_utf8_string($content) 
	{    	  
		$encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		return  mb_convert_encoding($content, 'utf-8', $encoding);
	}
function write_personinfo($xh,$pw) 
	{    	  
		$per_info_url='http://10.5.20.40/chengji/personinfo.php?xh='.$xh.'&pw='.$pw;
		$ret_personinfo=file_get_contents($per_info_url);
		return $ret_personinfo;
		
	}
	
/*
待用头
// $header[]='Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, text/html, *'.'/*';
// $header[]='Accept-Language: zh-cn';
// $header[]='User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)';
// $header[]='Host: 10.50.17.2';
// $header[]='Connection: Keep-Alive';
*/
?>