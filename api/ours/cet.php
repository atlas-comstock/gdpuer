<?PHP
require('cet_html_dom.php');
if(isset($_GET['zkzh']))
{
  $src='http://www.chsi.com.cn/cet/query';
  $id=$_GET['zkzh'];
  $name=$_GET['xm'];
  $ch=curl_init();
  curl_setopt_array(
      $ch,
      array(
        CURLOPT_URL => $src.'?zkzh='.$id.'&xm='.$name,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => false,
        CURLOPT_REFERER=> 'http://www.chsi.com.cn/cet/'
      )
    );
  $content=curl_exec($ch);
  if(curl_errno($ch)==0){
    $html = new simple_html_dom();
    $html->load($content);
    $table=$html->find('table[class=cetTable]',0);
    if(!$table){
      $str="请确认姓名或准考证号是否正确!";
	}else{
    $text=$table->outertext;
    $html->clear();
    $table->clear();
    unset($html);
	$str = $text;
	$str=str_replace('<table border="0" align="center" cellpadding="0" cellspacing="6" class="cetTable">  	<tr>  		<th>',"",$str);
	$str=str_replace('</th>  		<td>',"\t",$str);
	$str=str_replace('</td>  	</tr>  	<tr>  		<th>',"\n",$str);
	$str=str_replace('<strong><span style="color: #F00;">',"",$str);
	$str=str_replace('</span>',"\t",$str);
	$str=str_replace('&nbsp;&nbsp;',"",$str);
	$str=str_replace('<span class="color01">',"\n",$str);
	$str=str_replace('</strong></td>  	</tr>  </table>',"\n\n查询数据来源于学信网\nOURStudio提供技术支持.",$str);
    //}
    echo $str;
  }
  else
  {
  	echo curl_error ($ch);
  }
}

?>