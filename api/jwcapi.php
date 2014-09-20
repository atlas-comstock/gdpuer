<?php
header("content-Type: text/html; charset=utf-8");

$xh = $_REQUEST['xh'];
$pw = $_REQUEST['pw'];
$flag = $_REQUEST['flag'];
$j= new Jw();
$j->jwc($flag,$xh,$pw);
class Jw
{
    public function jwc($flag,$xh,$pw)
    {

        switch($flag){
            case "1":
                $res=$this->get_personinfo($xh,$pw);
                break;
            case "2":
                $res=$this->get_chengji($xh,$pw);
                break;
            case "3":
                $res=$this->get_xuanxiu($xh,$pw);
                break;
            case "4":
                $res=$this->get_personinfo_xuanxiu($xh,$pw);
                break;
            default:
                break;
        }
        echo $res;
    }

    public function get_utf8_string($content)
    {
        $encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
        return  mb_convert_encoding($content, 'utf-8', $encoding);
    }

    public function get_personinfo($xh,$pw)
    {
        $per_info_url='http://ours.123nat.com:59832/helper/api/chengji/personinfo.php?xh='.$xh.'&pw='.$pw;
        $ret_personinfo=file_get_contents($per_info_url);
        return $ret_personinfo;
    }

    public function get_personinfo_xuanxiu($xh,$pw)
    {
        $per_info_url='http://ours.123nat.com:59832/helper/api/chengji/personinfo_xuanxiu.php?xh='.$xh.'&pw='.$pw;
        $ret_personinfo=file_get_contents($per_info_url);
        return $ret_personinfo;
    }

    public function get_chengji($xh,$pw)
    {
        $per_info_url='http://ours.123nat.com:59832/helper/api/chengji/chengjiapi.php?xh='.$xh.'&pw='.$pw;
        $ret_personinfo=file_get_contents($per_info_url);
        return $ret_personinfo;
    }

    public function get_xuanxiu($xh,$pw)
    {
        $per_info_url='http://ours.123nat.com:59832/helper/api/chengji/xuanxiuapi.php?xh='.$xh.'&pw='.$pw;
        $ret_personinfo=file_get_contents($per_info_url);
        return $ret_personinfo;
    }
}
?>
