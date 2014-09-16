<?php
/** 
 * File: index.php
 * 该 php 实现了广药小助手微信公众平台的全部功能            
 * 微信账号 gdpuer       
 * 作者：chaowenliu  & Yanson
 * 原文：http://weibo.com/cheman1989    
 * 时间：2013.4.4    
 */

header("content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . "/wechat.php");
require_once("../api/webAPI.php");
define("DEBUG", true);

//下面为需要配置的选项
define("TOKEN", "gdpuer");
//填写自定义机器人名称http://www.xiaojo.com/myadmin/pages/wx.php?id=1450
define("YOURNICK", "广药小助手");
//在这里定义你的初次关注后的欢迎语和菜单@title|【网站导航】- 点击进入#url|http://ourstudio.duapp.com/menu/gdpuer/website.php#pic
define("WELCOME", "description|问候#title|欢迎关注@title|留言：*你的话\t聊天：任意回复#url|#pic@title|绑定：昵称XXX\t菜单：回复 ? 号#url|#pic@title|调教：问X答X \t关于：点击查看#url|http://www.xiaojo.com/myadmin/pages/wx.php?id=1450#pic");
define("MENU","description|菜单#title|功能向导@title|【校园资讯】- 回复数字\n\n[1]广药新闻\t\t[2]就业信息\n[3]图书信息\t\t[4]查四六级\n[5]开户进度\t\t[6]网络账号\n[7]发微波炉\t\t[8]发找找帮\n[9]勤管兼职\t\t[10]查成绩\n[11]查选修\t\t#url|http://ourstudio.duapp.com/menu/gdpuer/campus.php#pic@title|【生活服务】- 回复字母\n\n[A]听歌\t[B]公交\t[C]游戏\t[D]天气\n[E]翻译\t[F]快递\t[G]解梦\t[H]火车\n[I]手机\t[J]身份\t[K]百科\t[L]昵称\n[M]音乐\t[N]股票#url|http://ourstudio.duapp.com/menu/gdpuer/live.php#pic@title|留言：*你的话\t聊天：任意回复#url|#pic@title|绑定：昵称XXX\t调教：问X答X#url|#pic");
define("TEXT","【校园资讯-回复数字】\n[1]广药新闻\t[2]就业信息\n[3]图书信息\t[4]查四六级\n[5]开户进度\t[6]网络账户\n[7]发微波炉\t[8]发找找帮\n[9]勤管兼职\t[10]查成绩\n[11]查选修\n\n【生活服务-回复字母】\n[A]听歌\t[B]公交\t[C]游戏\n[D]天气\t[E]翻译\t[F]快递\n[G]解梦\t[H]火车\t[ I ]手机\n[ J ]身份\t[K]百科\t[L]昵称\n[M]音乐\t[N]股票\t\n\n聊天： 任意回复\n留言： *+你的话\n绑定： 昵称XXX\n调教： 问XX答XX");
//星标标识，默认为* ,用户对话里包含此标识则设置为星标，用于留言
define("FLAG", "*");
//这里为你的私有库账号
$yourdb="gdpuer";
$yourpw="ourstudio";
$welcome='欢迎关注广药小助手';
//配置结束

$w = new Wechat(TOKEN, DEBUG);
//首次验证，验证过以后可以删掉
if (isset($_GET['echostr'])) {
    $w->valid();
    exit();
}

//回复用户
$w->reply("reply_main");
//后续必要的处理...
/* TODO */
exit();
function reply_main($request, $w)
{
    $to = $request['ToUserName'];
    $from = $request['FromUserName'];
    //大众接口
    if ($w->get_msg_type() == "location") {
        $lacation = "x@".(string)$request['Location_X']."@".(string)$request['Location_Y'];
        $lacation = urlencode(str_replace('\.','\\\.',$lacation));
        $lacation = urldecode(xiaojo($lacation,$from,$to));
        return  $lacation;
    }
    //返回图片地址
    else if ($w->get_msg_type() == "image") { 
        $PicUrl = $request['PicUrl'];
        $w->set_funcflag();
        return "咦,我也有这张照片：" . $PicUrl;
    }
    //用户发语音时回复语音或音乐
    else if ($w->get_msg_type() == "voice") {
        return array(
                "title" =>  "你好",
                "description" =>  "亲爱的主人",           
                "murl" =>  "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",
                "hqurl" =>  "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",
                );
    }
    //事件检测
    else if ($w->get_msg_type() == "event") { 
        //关注
        if ($w->get_event_type() == "subscribe"){
            $welcome=WELCOME ;
            return $welcome;
        }
        //取消关注
        elseif($w->get_event_type() == "unsubscribe"){
            $unsub = urldecode(xiaojo("subscribe",$from,$to));
            return $unsub;
        }
        //点击菜单
        elseif($w->get_event_type() == "click"){
            $menukey = $w->get_event_key();
            $menu = urldecode(xiaojo($menukey,$from,$to));
            return $menu;
        }
        //点击菜单选项
        else{
            $menukey = $w->get_event_key();
            return $menukey;
        }
    }
    //获取http的content字段
    $content = trim($request['Content']);
    if(!empty($content)){
        $flag="0";
        //广药内网接口
        if($content=="?"||$content=='？'){$flag='menu';}
        //<a href=\"weixin://contacts/profile/gh_fc78851e31a5\">点击关注</a>
        else if(strstr($content,"绑定")){return $content="【管理员回复】\n\n绑定以及相关功能目前还在内测，还没有接入到本平台，可以加微信号doctoryanson(Y博士)或者gdpucafe(广药淅水咖啡厅)进行测试>>>\n\n有菜单版本更加方便快捷<a href=\"weixin://contacts/profile/gh_a450baf872ec\">点击关注</a>";}
        else if($content=="菜单"||$content=='帮助'||$content=="列表"||$content=='清单'||$content=='功能'){$flag="text";}
        else if(strstr($content,"开户")){$flag="5";}
        else if(strstr($content,"网号")){$flag="6";}
        //广药外网接口
        else if($content=="1"||$content=="2"||$content=="9"||strstr($content,"还书")||strstr($content,"图书")){$flag="gdpuapi";}
        //网页外部接口
        else if(strstr($content,"表白")||strstr($content,"绑定")||strstr($content,"cet")||strstr($content,"Cet")||strstr($content,"CET6")||strstr($content,"CET")||strstr($content,"cet4")||strstr($content,"cet6")||strstr($content,"CET4")||strstr($content,"4")||strstr($content,"四级")||strstr($content,"六级")||strstr($content,"4级")||strstr($content,"6级")||strstr($content,"归属地")||strstr($content,"解梦")||strstr($content,"身份证")||strstr($content,"找找帮")||strstr($content,"音乐")||strstr($content,"视频")||$content=="A"||$content=="a"){$flag="webapi";}

        //成绩查询
        else if(strstr($content,"成绩")||strstr($content,"10")){
            $content=str_replace('＃','#',$content);
            $ret=explode('#',$content);
            $xh=$ret[1];
            $pw=$ret[2];
            if(($xh)&&($pw)){
                $url = 'http://ours.123nat.com:59832/helper/api/jwcapi.php?xh='.$xh.'&pw='.$pw.'&flag=2';
                $content = file_get_contents($url);
                //			$content = explode("2014学年",$content);
                //			$content = $content[1];
                //			$content = str_replace('2013','-',$content);
                $content = substr($content,0,1900);
            }elseif((!$xh)||(!$pw)){
                $content="请确认【格式】是否正确\n\n成绩#学号#密码";
            }else{
                $content="请确认格式是否正确\n\n成绩#学号#密码";
            }
            return $content;
        }
        //课表查询
        else if(strstr($content,"课表")||strstr($content,"7")){
            $content=str_replace('＃','#',$content);
            $ret=explode('#',$content);
            $xh=$ret[1];
            $pw=$ret[2];
            $day=date("w");
            if(isset($ret[3])){
                if(($ret[3]>=1)&&($ret[3]<=5))
                {
                    $day=$ret[3];
                }
                if((strtolower($ret[3])=='all'))
                {
                    $day='all';
                }
            }
            if(($xh)&&($pw)){
                $url = 'http://ours.123nat.com:59832/helper/kb/kb.php?xh='.$xh.'&pw='.$pw.'&day='.$day;	
                    $content= file_get_contents($url);
            }elseif((!$xh)||(!$pw)){
                $content="【现已支持所有校区】\n按照以下格式获取课表\n\n【今天课表】\n课表#学号#密码\n\n【周X课表】\n课表#学号#密码#X\n\n(X为1-5,或者是all，否则均默认为当天，周六、日显示全部课表)\n\n【例如】\n获取今天课表：\n课表#1207511199#1207511199\n\n获取周1课表：\n课表#1207511199#1207511199#1\n\n获取全部课表：\n课表#1207511199#1207511199#all";
            }else{
                $content="【现已支持所有校区】\n按照以下格式获取课表\n\n【今天课表】\n课表#学号#密码\n\n【周X课表】\n课表#学号#密码#X\n\n(X为1-5,或者是all，否则均默认为当天，周六、日显示全部课表)\n\n【例如】\n获取今天课表：\n课表#1207511199#1207511199\n\n获取周1课表：\n课表#1207511199#1207511199#1\n\n获取全部课表：\n课表#1207511199#1207511199#all";
            }
            return $content;
        }


        //选修查询
        else if(strstr($content,"11")||strstr($content,"选修")){
            $content=str_replace('＃','#',$content);
            $ret=explode('#',$content);
            $xh=$ret[1];
            $pw=$ret[2];
            if(($xh)&&($pw)){
                $url = 'http://ours.123nat.com:59832/helper/jwc/wx.xuanxiu.api.php?xh='.$xh.'&pw='.$pw;
                $content= file_get_contents($url);
            }elseif((!$xh)||(!$pw)){
                $content="请确认【格式】是否正确\n\n选修#学号#密码";
            }else{
                $content="请确认【格式】是否正确\n\n选修#学号#密码";
            }
            return $content;
        }

        else    if(strstr($content,"4")||strstr($content,"cet")||strstr($content,"Cet")||strstr($content,"四级")||strstr($content,"六级")){
            if(strstr($content,"4")||strstr($content,"cet")||strstr($content,"Cet")||strstr($content,"四级")||strstr($content,"六级")){
                $content = trim($content);
                $content=str_replace('＃','#',$content);
                $ret=explode('#',$content);
                $zkzh=trim($ret[1]);
                $xm = trim($ret[2]);
                if($zkzh==''||$xm==''){$content="【CET4，6级查询】\nby Ourstudio工作室\n请检查格式是否正确\n发送\n\ncet#准考证号#姓名\n\n即可查询";}
                if($zkzh && $xm){
                    $url = 'http://ours.123nat.com:59832/helper/chengji/cet_wx.php?zkzh='.$zkzh.'&xm='.$xm;
                    $content= file_get_contents($url);
                    if ($content=="")
                        $content="无法查找到你的成绩，请检查学号、姓名是否正确\n";
                    return $content;
                    //  $content="请确认信息全部正确，比如名字一定要全称，不能简写。收到最新消息称要到9点各网站才开通查询4，6级成绩";
                }
            }
        }

        //动漫更新查询
        else if(strstr($content,"动漫")){
            $url = 'http://110.75.189.200/chris/helper/media_update.php';
            $content = file_get_contents($url);
            return $content;
        }
        //menu内容
        if($flag=="menu" || strstr($content,"查询")){$content=MENU;}  
        //menu内容
        else if($flag=="text"){$content=TEXT;}
        //通过广药内网接口获得返回内容
        else if($flag=="5"||$flag=="6"){
            if($flag=="5"){$content=ltrim($content,"开户");}
            else if($flag=="6"){$content=ltrim($content,"网号");}
            $url = 'http://zlgc.gdpu.edu.cn/gdpuer/api.php?flag='.$flag.'&content='.$content;		
                $content= file_get_contents($url);
        }
        //通过广药外网网接口获得返回内容
        else if($flag=="gdpuapi"){
            $g=new WebAPI();
            if($content=="1"){
                $content=$g->get_gdpu_news();
            }
            else if($content=="2"){
                $content="开发中";
                // $content=$g->get_gdpu_jobs();
            }
            else if($content=="9"){
                $content=$g->get_gdpu_partime();
            }
            else if(strstr($content,"图书")){
                $keyword=str_replace("图书","",$content);
                $content=$g->get_lib_book($keyword);
            }
            else if(strstr($content,"还书")){
                $keyword=str_replace("还书","",$content);
                $content=$g->get_lib_boorowbook($keyword);
            }
            else{
                $content="未知外网接口！";
            }
        }
        //通过外部接口获得返回内容
        else if($flag=="webapi"){
            $o=new WebAPI();

            if(strstr($content,"4")||strstr($content,"cet")||strstr($content,"Cet")||strstr($content,"四级")||strstr($content,"六级")){
                if(strstr($content,"4")||strstr($content,"cet")||strstr($content,"Cet")||strstr($content,"四级")||strstr($content,"六级")){
                    $content = trim($content);
                    $content=str_replace('＃','#',$content);
                    $ret=explode('#',$content);
                    $zkzh=trim($ret[1]);
                    $xm = trim($ret[2]);
                    if($zkzh==''||$xm==''){$content="【CET4，6级查询】\nby Ourstudio工作室\n请检查格式是否正确\n发送\n\ncet#准考证号#姓名\n\n即可查询";}
                    if($zkzh && $xm){
                        $url = 'http://ours.123nat.com:59832/helper/chengji/cet_wx.php?zkzh='.$zkzh.'&xm='.$xm;
                        $content= file_get_contents($url);
                    if ($content=="")
                        $content="无法查找到你的成绩，请检查学号、姓名是否正确\n";
                        return $content;
                        //	$content="请确认信息全部正确，比如名字一定要全称，不能简写。收到最新消息称要到9点各网站才开通查询4，6级成绩";
                    }
                }

                /*
                   $content=ltrim($content,"cet");
                   $regex = '/[0-9]*$/';
                   preg_match($regex, $content, $zkzh);
                   $xm=rtrim($content,$zkzh[0]);
                   $content=$o->get_ours_cet($zkzh[0],$xm);
                 */

            }
            else if(strstr($content,"归属地")){
                $number=ltrim($content,"归属地");
                $content=$o->get_ours_mobile($number);
            }
            else if(strstr($content,"解梦")){
                $key=str_replace('解梦','',$content);
                $content=$o->get_ours_dream($key);
            }
            else if($content=="l"||$content=="L"){
                $content=$o->get_ours_award();
            } 
            else if(strstr($content,"身份证")){
                $no=str_replace('身份证','',$content);
                $content=$o->get_ours_idcard($no);
            }
            else if(strstr($content,"找找帮")){
                $text=str_replace('找找帮','',$content);
                $content=$o->send_ours_zzbon($text,$from);
            }
            else if($content=="A"||$content=="a"){
                $content=$o->get_song_random();
            }
            else if(strstr($content,"音乐")){
                $content=$o->get_song_tencent($content);
                $content=mb_convert_encoding($content, 'utf-8', 'gbk');
            }
            else if(strstr($content,"视频")){
                $content=$o->get_video_youku($content);
            }
            else if(strstr($content,"表白")){
                $content=$o->get_biaobai($content,$from);
            }
            else{
                $content="未知外部接口！";
            }
        }
        //通过机器人接口获得返回内容
        else{
            //表情处理
            $content = $w->biaoqing($content); 
            //如果有星标的标记则设为星标(用于留言)
            if(strstr($content,FLAG)){ 
                $w->set_funcflag();
            }
            //	$url = 'http://xiao.douqq.com/api.php?msg='.$content.'&type=txt';
            $url = 'http://www.tuling123.com/openapi/api?key=2de48f93cfa6fb3fff1c0ede2ac8b953&info='.$content;
            $content= file_get_contents($url);
            preg_match_all('/{"code":100000,"text":"(.+?)"}/is',$content, $core);
            $content = $core[1][0];
            if(YOURNICK){
                $content = str_replace('小豆',YOURNICK,$content);
            }
            if($content==""){
                $content = "你说的话太深奥了，教我如何答你好呢？\n命令：问...答...\n";
            }
        }
    }
    else if($welcome!=''){$content=WELCOME;}
    //音乐地址
    if(strstr($content,'murl')){//音乐
        $a=array();
        foreach (explode('#',$content) as $content)
        {
            list($k,$v)=explode('|',$content);
            $a[$k]=$v;
        }
        $content = $a;
    }
    elseif(strstr($content,'pic'))//多图文回复
    {
        $a=array();
        $b=array();
        $c=array();
        $n=0;
        $contents = $content;
        foreach (explode('@t',$content) as $b[$n])
        {
            if(strstr($contents,'@t'))
            {
                $b[$n] = str_replace("itle","title",$b[$n]);
                $b[$n] = str_replace("ttitle","title",$b[$n]);
            }

            foreach (explode('#',$b[$n]) as $content)
            {
                list($k,$v)=explode('|',$content);
                $a[$k]=$v;
                $d.= $k;
            }
            $c[$n] = $a;
            $n++;

        }
        $content = $c ;
    }
    //最后返回
    return  $content;  
}

?>
