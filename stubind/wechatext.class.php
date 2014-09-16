<?php
/**
 *	微信公众平台PHP-SDK
 *  Wechatext为非官方微信发送API
 *  注: 用户id为通过getMsg()方法获取的FakeId值
 *  主要实现如下功能:
 *
 *  send($id,$content) 向某用户id发送微信文字信息
 *  getUserList($page,$pagesize,$group) 获取用户信息
 *  getGroupList($page,$pagesize) 获取群组信息
 *  sendNews($id,$msgid) 发送图文消息
 *  getNewsList($page,$pagesize) 获取图文信息列表
 *  uploadFile($username,$filepath,$type) 上传附件,包括图片/音频/视频
 *  addPreview($title,$author,$summary,$content,$photoid,$srcurl='')   创建新的图文信息 
 *  getFileList($type,$page,$pagesize) 获取素材库文件列表
 *  sendImage($id,$fid) 发送图片消息
 *  sendAudio($id,$fid) 发送音频消息
 *  sendVideo($id,$fid) 发送视频消息
 *  getInfo($id) 根据id获取用户资料
 *  getNewMsgNum($lastid) 获取从$lastid算起新消息的数目
 *  getTopMsg() 获取最新一条消息的数据, 此方法获取的消息id可以作为检测新消息的$lastid依据
 *  getMsg($lastid,$offset=0,$perpage=50,$day=0,$today=0,$star=0) 获取最新的消息列表, 列表将返回消息id, 用户id, 消息类型, 文字消息等参数
 *  消息返回结构:  {"id":"消息id","type":"类型号(1为文字,2为图片,3为语音)","fileId":"0","hasReply":"0","fakeId":"用户uid","nickName":"昵称","dateTime":"时间戳","content":"文字内容"} 
 *  getMsgImage($msgid,$mode='large') 若消息type类型为2, 调用此方法获取图片数据
 *  getMsgVoice($msgid) 若消息type类型为3, 调用此方法获取语音数据
 *  getMsgVideo($msgid) 若消息type类型为4, 调用此方法获取视频数据
 *  getHeadImg($fakeid) 获取用户头像
 *  getqrcode($fakeid,$large='3') 获取二维码 large='3' 二维码尺寸 1-5分别对应  258  344  430 860 1280 
 *  getappid() 获取APPID $info['key']  $info['secret']
 *  callbackprofile($url,$callback_token)  设置接口地址 $url token
 *   getticket() 获取上传时候的ticket
 *  getbasicinfo()  获取公众平台基本信息 
	$info=Array
	(
		[fakeid] => 
		[headimg] =>
		[qrcode] => 
		[original] =>
		[name] => 
		[account] => 
		[signature] => 
		[key] => 
		[secret] => 
		[country] => 
		[province] => 
		[city] => 
	)
 *
 *  @author dodge <dodgepudding@gmail.com>
 *  @link https://github.com/dodgepudding/wechat-php-sdk
 *  @version 1.2
 *  
 */

include "snoopy.class.php";
class Wechatext
{
	private $cookie;
	private $_cookiename;
	private $_cookieexpired = 3600;
	private $_account;
	private $_password;
	private $_datapath = './data/cookie_';
	private $debug;
	private $_logcallback;
	private $_token;
	
	public function __construct($options)
	{
		$this->_account = isset($options['account'])?$options['account']:'';
		$this->_password = isset($options['password'])?$options['password']:'';
		$this->_datapath = isset($options['datapath'])?$options['datapath']:$this->_datapath;
		$this->debug = isset($options['debug'])?$options['debug']:false;
		$this->_logcallback = isset($options['logcallback'])?$options['logcallback']:false;
		$this->_cookiename = $this->_datapath.$this->_account;
		$this->cookie = $this->getCookie($this->_cookiename);
	}

	/**
	 * 主动发消息
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $content 发送的内容
	 */
	public function send($id,$content)
	{
		$send_snoopy = new Snoopy; 
		$post = array();
		$post['tofakeid'] = $id;
		$post['type'] = 1;
		$post['token'] = $this->_token;
		$post['content'] = $content;
		$post['ajax'] = 1;
        $send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/singlesendpage?t=message/send&action=index&tofakeid=$id&token={$this->_token}&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		return $send_snoopy->results;
	}
	
	/**
	 * 群发功能 纯文本
	 * @param string $content
	 * @return string
	 */
	public function mass($content) {
		$send_snoopy = new Snoopy;
		$post = array();
		$post['type'] = 1;
		$post['token'] = $this->_token;
		$post['content'] = $content;
		$post['ajax'] = 1;
		$post['city']='';
		$post['country']='';
		$post['f']='json';
		$post['groupid']='-1';
		$post['imgcode']='';
		$post['lang']='zh_CN';
		$post['province']='';
		$post['random']=  rand(0, 1);
		$post['sex']=0;
		$post['synctxnews']=0;
		$post['synctxweibo']=0;
		$post['t']='ajax-response';
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token={$this->_token}&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/masssend";
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		return $send_snoopy->results;
	}
	
	/**
	 * 群发功能 图文素材
	 * @param int $appmsgid 图文素材ID
	 * @return string
	 */
	function massNews($appmsgid){
		$send_snoopy = new Snoopy;
		$post = array();
		$post['type'] = 10;
		$post['token'] = $this->_token;
		$post['appmsgid'] = $appmsgid;
		$post['ajax'] = 1;
		$post['city']='';
		$post['country']='';
		$post['f']='json';
		$post['groupid']='-1';
		$post['imgcode']='';
		$post['lang']='zh_CN';
		$post['province']='';
		$post['random']=  rand(0, 1);
		$post['sex']=0;
		$post['synctxnews']=0;
		$post['synctxweibo']=0;
		$post['t']='ajax-response';
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token={$this->_token}&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/masssend";
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		return $send_snoopy->results;
	}
	
	/**
	 * 获取用户列表列表
	 * @param $page 页码(从0开始)
	 * @param $pagesize 每页大小
	 * @param $groupid 分组id
	 * @return array ({contacts:[{id:12345667,nick_name:"昵称",remark_name:"备注名",group_id:0},{}....]})
	 */
	function getUserList($page=0,$pagesize=10,$groupid=0){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=".$pagesize."&pageidx=".$page."&type=0&groupid=0&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=".$pagesize."&pageidx=".$page."&type=0&groupid=$groupid&lang=zh_CN&f=json&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('userlist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['contact_list'])) {
			$json = json_decode($json['contact_list'],true);
			if (isset($json['contacts']))
				return $json['contacts'];
		}
		return false;
	}
	
	/**
	 * 获取分组列表
	 * 
	 */
	function getGroupList(){
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid=0&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid=0&lang=zh_CN&f=json&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('userlist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['group_list'])){
			$json = json_decode($json['group_list'],true);
			if (isset($json['groups']))
				return $json['groups'];
		}
		return false;
	}
	
	/**
	 * 获取图文信息列表
	 * @param $page 页码(从0开始)
	 * @param $pagesize 每页大小
	 * @return array
	 */
	public function getNewsList($page,$pagesize=10) {
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$type=10;
		$begin = $page*$pagesize;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/appmsg?token=".$this->_token."&lang=zh_CN&type=$type&action=list&begin=$begin&count=$pagesize&f=json&random=0.".$t;
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('newslist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['app_msg_info'])) {
			return $json['app_msg_info'];
		} 
		return false;
	}
	
	/**
	 * 获取与指定用户的对话内容
	 * @param  $fakeid
	 * @return  array
	 */
	public function getDialogMsg($fakeid) {
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesendpage?t=message/send&action=index&tofakeid=".$fakeid."&token=".$this->_token."&lang=zh_CN&f=json&random=".$t;
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('DialogMsg:'.$result);
		$json = json_decode($result,true);
		if (isset($json['page_info'])) {
			return $json['page_info'];
		}
		return false;
	}
	
	
	/**
	 * 发送图文信息,必须从图文库里选取消息ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $msgid 图文消息id
	 */
	public function sendNews($id,$msgid)
	{
		$send_snoopy = new Snoopy; 
		$post = array();
		$post['tofakeid'] = $id;
		$post['type'] = 10;
		$post['token'] = $this->_token;
		$post['fid'] = $msgid;
		$post['appmsgid'] = $msgid;
		$post['error'] = 'false';
		$post['ajax'] = 1;
        $send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/singlemsgpage?fromfakeid={$id}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		return $send_snoopy->results;
	}
	

	
	/**
	 * 上传附件(图片/音频/视频)
	 * @param string $username 用户ticket_id名称
	 * @param string $filepath 本地文件地址
	 * @param int $type 文件类型: 2:图片 3:音频 4:视频
	 */
	public function uploadFile($filepath,$type=2) {
		$send_snoopy = new Snoopy;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/filepage?type=2&begin=0&count=10&t=media/list&token=". $this->_token . "&lang=zh_CN";
		//$t = time().strval(mt_rand(100,999));
		$post['Filename'] = '';
		$post['folder'] = '/cgi-bin/uploads';
		$postfile = array('file'=>$filepath);
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->set_submit_multipart();
		$submit = "https://mp.weixin.qq.com/cgi-bin/filetransfer?action=upload_material&f=json&ticket_id=". $this->_account ."&ticket=".$this->getticket() ."&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->submit($submit,$post,$postfile);
		$tmp = $send_snoopy->results;
		$this->log('upload:'.$tmp);
		$ret= json_decode($tmp,1);
		$fileid=$ret['content'];
		return $fileid;
	}
	
	/**
	 * 设置接口
	 * @param $url 接口地址
	 * @param $callback_token token
	 * 
	 */
	public function callbackprofile($url,$callback_token) {
	
		$send_snoopy = new Snoopy; 
		$this->advancedswitch();
		$post = array();
		$post['url'] = $url;
		$post['callback_token'] = $callback_token;
        $send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=interface&t=advanced/interface&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/advanced/callbackprofile?t=ajax-response&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log('callbackprofile:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['ret']==0) 
			return true;
		else
			return false;
	}
	
	/**
	 * 如果有自定义菜单获取APPID AppSecret
	 * 
	 * 
	 * @return $info['key']  $info['secret']
	 */
	public function getappid() {
		$info=array();
		$send_snoopy = new Snoopy; 
        $send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->submit($submit);
		$result = $send_snoopy->results;
		$this->log('getappid:'.$result);
		preg_match_all("/value\:\"(.*?)\"/", $result, $match);
		$info['key'] = $match[1][2];
		$info['secret'] = $match[1][3];
		return $info;
	}
	
	/**
	 * 设置开发者模式开
	 * @param $url 接口地址
	 * @param $callback_token token
	 * 
	 */
	public function advancedswitch() {
		$send_snoopy = new Snoopy; 
		$post = array();
		$post['flag'] = 1;
		$post['type'] = 2;
		$post['token'] = $this->_token;
		
        $send_snoopy->referer = "https://mp.weixin.qq.com/advanced/advanced?action=edit&t=advanced/edit&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/misc/skeyform?form=advancedswitchform&lang=zh_CN";
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		var_dump($result);
		$this->log('callbackprofile:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['ret']==0) 
			return true;
		else
			return false;
	}
	
	/**
	 * 获取上传时候的ticket
	 * 
	 * 
	 */
	public function getticket() 
	{	
		$info = array();
		$send_snoopy = new Snoopy; 
		//$post['token'] = $this->_token;
        $send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->submit($submit);
		$this->log($send_snoopy->results);
		$response=$send_snoopy->results;
		preg_match('/ticket:"([a-z0-9A-Z]+)"/', $response, $match);
		$ticket = $match[1];
		return $ticket;
	}
	
	
	/**
	 * 获取公众平台基本信息
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $msgid 图文消息id
	 */
	public function getbasicinfo() 
	{	
		$info = array();
		$send_snoopy = new Snoopy; 
		//$post['token'] = $this->_token;
        $send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&token=". $this->_token ."&lang=zh_CN";
		$send_snoopy->submit($submit);
		$this->log($send_snoopy->results);
		$response=$send_snoopy->results;
		preg_match('/fakeid=([0-9]+)/', $response, $match);
		$fakeid = $match[1];
		$info['fakeid']=$fakeid;
		//https://mp.weixin.qq.com/misc/getheadimg?token=552640670&fakeid=2398097422&r=608901
		$info['headimg'] = $this->getHeadImg($fakeid);
		$info['qrcode']= $this->getqrcode($fakeid);
		$header = implode(',',$send_snoopy->headers);
		preg_match('/slave_user=(gh_[a-z0-9A-Z]+)/', $header, $match);
		$info['original'] = $match[1];	
		preg_match('/名称([\s\S]+?)<\/li>/', $response, $match);
		$info['name'] = trim(strip_tags($match[1]));
		preg_match('/微信号([\s\S]+?)<\/li>/', $response, $match);
		$info['account'] = trim(strip_tags($match[1]));
		preg_match('/功能介绍([\s\S]+?)meta_content\">([\s\S]+?)<\/li>/', $response, $match);
		$info['signature'] = trim(strip_tags($match[2]));
		if ((!(strpos($response, '服务号') === FALSE)) || (!(strpos($response, '微信认证') === FALSE)))
		{
		$app= $this->getappid();
		$info['key'] = $app['key'];
		$info['secret'] = $app['secret'];
		}
		preg_match_all("/(?:country|province|city): '(.*?)'/", $response, $match);
		$info['country'] = trim($match[1][0]);
		$info['province'] = trim($match[1][1]);
		$info['city'] = trim($match[1][2]);
		return $info;
	}
	
	
	/**
	 * 创建图文消息
	 * @param array $title 标题
	 * @param array $summary 摘要
	 * @param array $content 内容
	 * @param array $photoid 素材库里的图片id(可通过uploadFile上传后获取)
	 * @param array $srcurl 原文链接
	 * @return json
	 */
	public function addPreview($title,$author,$summary,$content,$photoid,$srcurl='') {
		$send_snoopy = new Snoopy;
		$send_snoopy->referer = 'https://mp.weixin.qq.com/cgi-bin/operate_appmsg?lang=zh_CN&sub=edit&t=wxm-appmsgs-edit-new&type=10&subtype=3&token='.$this->_token;
		
		$submit = "https://mp.weixin.qq.com/cgi-bin/operate_appmsg?lang=zh_CN&t=ajax-response&sub=create&token=".$this->_token;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		
		$send_snoopy->set_submit_normal();
		$post = array(
				'token'=>$this->_token,
				'type'=>10,
				'lang'=>'zh_CN',
				'sub'=>'create',
				'ajax'=>1,
				'AppMsgId'=>'',				
				'error'=>'false',
		);
		if (count($title)==count($author)&&count($title)==count($summary)&&count($title)==count($content)&&count($title)==count($photoid))
		{
			$i = 0;
			foreach($title as $v) {
				$post['title'.$i] = $title[$i];
				$post['author'.$i] = $author[$i];
				$post['digest'.$i] = $summary[$i];
				$post['content'.$i] = $content[$i];
				$post['fileid'.$i] = $photoid[$i];
				if ($srcurl[$i]) $post['sourceurl'.$i] = $srcurl[$i];
				
				$i++;
				}
		}
		$post['count'] = $i;
		$post['token'] = $this->_token;
		$send_snoopy->submit($submit,$post);
		$tmp = $send_snoopy->results;
		$this->log('step2:'.$tmp);
		$json = json_decode($tmp,true);
		return $json;
	}
	
	/**
	 * 发送媒体文件
	 * @param $id 用户的uid(即FakeId)
	 * @param $fid 文件id
	 * @param $type 文件类型
	 */
	public function sendFile($id,$fid,$type) {
		$send_snoopy = new Snoopy; 
		$post = array();
		$post['tofakeid'] = $id;
		$post['type'] = $type;
		$post['token'] = $this->_token;
		$post['fid'] = $fid;
		$post['fileid'] = $fid;
		$post['error'] = 'false';
		$post['ajax'] = 1;
        $send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/singlemsgpage?fromfakeid={$id}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response";
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		$this->log('sendfile:'.$result);
		$json = json_decode($result,true);
		if ($json && $json['ret']==0) 
			return true;
		else
			return false;
	}
	
	/**
	 * 获取素材库文件列表
	 * @param $type 文件类型: 2:图片 3:音频 4:视频
	 * @param $page 页码(从0开始)
	 * @param $pagesize 每页大小
	 * @return array
	 */
	public function getFileList($type,$page,$pagesize=10) {
		$send_snoopy = new Snoopy;
		$t = time().strval(mt_rand(100,999));
		$begin = $page*$pagesize;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/masssendpage?t=mass/send&token=".$this->_token."&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$submit = "https://mp.weixin.qq.com/cgi-bin/filepage?token=".$this->_token."&lang=zh_CN&type=$type&random=0.".$t."&begin=$begin&count=$pagesize&f=json";
		$send_snoopy->fetch($submit);
		$result = $send_snoopy->results;
		$this->log('filelist:'.$result);
		$json = json_decode($result,true);
		if (isset($json['page_info']))
			return $json['page_info'];
		else
			return false;
	}
	
	/**
	 * 发送图文信息,必须从库里选取文件ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $fid 文件id
	 */
	public function sendImage($id,$fid)
	{
		return $this->sendFile($id,$fid,2);
	}
	
	/**
	 * 发送语音信息,必须从库里选取文件ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $fid 语音文件id
	 */
	public function sendAudio($id,$fid)
	{
		return $this->sendFile($id,$fid,3);
	}
	
	/**
	 * 发送视频信息,必须从库里选取文件ID发送
	 * @param  string $id      用户的uid(即FakeId)
	 * @param  string $fid 视频文件id
	 */
	public function sendVideo($id,$fid)
	{
		return $this->sendFile($id,$fid,4);
	}
	
	/**
	 * 发送预览图文消息
	 * @param string $account 账户名称(user_name)
	 * @param string $title 标题
	 * @param string $summary 摘要
	 * @param string $content 内容
	 * @param string $photoid 素材库里的图片id(可通过uploadFile上传后获取)
	 * @param string $srcurl 原文链接
	 * @return json
	 */
	public function sendPreview($account,$title,$summary,$content,$photoid,$srcurl='') {
		$send_snoopy = new Snoopy;
		$submit = "https://mp.weixin.qq.com/cgi-bin/operate_appmsg?sub=preview&t=ajax-appmsg-preview";
		$send_snoopy->set_submit_normal();
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = 'https://mp.weixin.qq.com/cgi-bin/operate_appmsg?sub=edit&t=wxm-appmsgs-edit-new&type=10&subtype=3&lang=zh_CN';
		$post = array(
				'AppMsgId'=>'',
				'ajax'=>1,
				'content0'=>$content,
				'count'=>1,
				'digest0'=>$summary,
				'error'=>'false',
				'fileid0'=>$photoid,
				'preusername'=>$account,
				'sourceurl0'=>$srcurl,
				'title0'=>$title,
		);
		$post['token'] = $this->_token;
		$send_snoopy->submit($submit,$post);
		$tmp = $send_snoopy->results;
		$this->log('sendpreview:'.$tmp);
		$json = json_decode($tmp,true);
		return $json;
	}
	
	/**
	 * 获取用户的信息
	 * @param  string $id 用户的uid(即FakeId)
	 * @return array  {fake_id:100001,nick_name:'昵称',user_name:'用户名',signature:'签名档',country:'中国',province:'广东',city:'广州',gender:'1',group_id:'0'},groups:{[id:0,name:'未分组',cnt:20]}
	 */
	public function getInfo($id)
	{
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$t = time().strval(mt_rand(100,999));
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/cgi-bin/getcontactinfo";
		$post = array('ajax'=>1,'lang'=>'zh_CN','random'=>'0.'.$t,'token'=>$this->_token,'t'=>'ajax-getcontactinfo','fakeid'=>$id);
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		$result = json_decode($send_snoopy->results,true);
		if(isset($result['contact_info'])){
			return $result['contact_info'];
		}
		return false;
	}
	
	/**
	 * 获得头像数据
	 *
	 * @param FakeId $fakeid
	 * @return JPG二进制数据
	 */
	public function getHeadImg($fakeid){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/misc/getheadimg?fakeid=$fakeid&token=".$this->_token ."&lang=zh_CN";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('Head image:'.$fakeid.'; length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}
	
	
	/**
	 * 获得二维码
	 *
	 * @param FakeId $fakeid
	 * @param large='3' 二维码尺寸 1-5分别对应  258  344  430 860 1280 
	 * @return JPG二进制数据
	 */
	public function getqrcode($fakeid,$large='3'){
		$info=array();
		$send_snoopy = new Snoopy;
		if($large=='1')
		{
		$size='224';
		$true_size='258';
		}
		else if($large=='2')
		{
		$size='336';
		$true_size='344';
		}
		else if($large=='3')
		{
		$size='420';
		$true_size='430';
		}
		else if($large=='4')
		{
		$size='840';
		$true_size='860';
		}
		else if($large=='5')
		{
		$size='1400';
		$true_size='1280';
		}
				
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&token=".$this->_token."&lang=zh_CN";
		$url = "https://mp.weixin.qq.com/misc/getqrcode?fakeid=" . $fakeid . "&token=" . $this->_token . "&style=1&pixsize=".$size;
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('Qrcode image:'.$fakeid.'; length:'.strlen($result));
		if(!$result){
			return false;
		}
		$info['size']=$true_size;
		$info['qrcode']=$result;
		return $info;
	}

	/**
	 * 获取消息更新数目
	 * @param int $lastid 最近获取的消息ID,为0时获取总消息数目
	 * @return int 数目
	 */
	public function getNewMsgNum($lastid=0){
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/cgi-bin/getnewmsgnum?t=ajax-getmsgnum&lastmsgid=".$lastid;
		$post = array('ajax'=>1,'token'=>$this->_token);
		$send_snoopy->submit($submit,$post);
		$this->log($send_snoopy->results);
		$result = json_decode($send_snoopy->results,1);
		if(!$result){
			return false;
		}
		return intval($result['newTotalMsgCount']);
	}
	
	/**
	 * 获取最新一条消息
	 * @return array {"id":"最新一条id","type":"类型号(1为文字,2为图片,3为语音)","fileId":"0","hasReply":"0","fakeId":"用户uid","nickName":"昵称","dateTime":"时间戳","content":"文字内容","playLength":"0","length":"0","source":"","starred":"0","status":"4"}        
	 */
	public function getTopMsg(){
		$send_snoopy = new Snoopy;
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&lang=zh_CN&token=".$this->_token;
		$submit = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&f=json&count=20&day=7&lang=zh_CN&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;
		$json = json_decode($result,true);
		if (isset($json['msg_items'])) {
			$json = json_decode($json['msg_items'],true);
			if(isset($json['msg_item']))
				return array_shift($json['msg_item']);
		}
		return false;
	}
	
	/**
	 * 获取新消息
	 * @param $lastid 传入最后的消息id编号,为0则从最新一条起倒序获取
	 * @param $offset lastid起算第一条的偏移量
	 * @param $perpage 每页获取多少条
	 * @param $day 最近几天消息(0:今天,1:昨天,2:前天,3:更早,7:五天内)
	 * @param $today 是否只显示今天的消息, 与$day参数不能同时大于0
	 * @param $star 是否星标组信息
	 * @return array[] 同getTopMsg()返回的字段结构相同
	 */
	public function getMsg($lastid=0,$offset=0,$perpage=20,$day=7,$today=0,$star=0){
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&lang=zh_CN&count=50&token=".$this->_token;
		$lastid = $lastid===0 ? '':$lastid;
		$addstar = $star?'&action=star':'';
		$submit = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&f=json&lang=zh_CN{$addstar}&count=$perpage&timeline=$today&day=$day&frommsgid=$lastid&offset=$offset&token=".$this->_token;
		$send_snoopy->fetch($submit);
		$this->log($send_snoopy->results);
		$result = $send_snoopy->results;
		$json = json_decode($result,true);
		if (isset($json['msg_items'])) {
			$json = json_decode($json['msg_items'],true);
			if(isset($json['msg_item']))
				return $json['msg_item'];
		}
		return false;
	}
	
	/**
	 * 获取图片消息
	 * @param int $msgid 消息id
	 * @param string $mode 图片尺寸(large/small)
	 * @return jpg二进制文件
	 */
	public function getMsgImage($msgid,$mode='large'){
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/getimgdata?token=".$this->_token."&msgid=$msgid&mode=$mode&source=&fileId=0";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('msg image:'.$msgid.';length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}
	
	/**
	 * 获取语音消息
	 * @param int $msgid 消息id
	 * @return mp3二进制文件
	 */
	public function getMsgVoice($msgid){
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/getvoicedata?token=".$this->_token."&msgid=$msgid&fileId=0";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('msg voice:'.$msgid.';length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}
	
	/**
	 * https://mp.weixin.qq.com/cgi-bin/getvideodata?msgid=200205645&fileid=&token=552640670
	 * 获取视频消息
	 * @param int $msgid 消息id
	 * @return mp3二进制文件
	 */
	public function getMsgVideo($msgid){
		$send_snoopy = new Snoopy; 
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->referer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&lang=zh_CN&count=50&token=".$this->_token;
		$url = "https://mp.weixin.qq.com/cgi-bin/getvideodata?token=".$this->_token."&msgid=$msgid&fileId=0";
		$send_snoopy->fetch($url);
		$result = $send_snoopy->results;
		$this->log('msg video:'.$msgid.';length:'.strlen($result));
		if(!$result){
			return false;
		}
		return $result;
	}
	
	
	
	/**
	 * 模拟登录获取cookie
	 * @return [type] [description]
	 */
	/**
	 * 模拟登录获取cookie
	 * @return [type] [description]
	 */
	public function login(){
		$snoopy = new Snoopy; 
		$submit = "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN";
		$post["username"] = $this->_account;
		$post["pwd"] = md5($this->_password);
		$post["f"] = "json";
		$post["imgcode"] = "";
		$snoopy->referer = "https://mp.weixin.qq.com/";
		$snoopy->submit($submit,$post);
		$cookie = '';
		$this->log($snoopy->results);
		$result = json_decode($snoopy->results,true);
		
		if (!isset($result['base_resp']) || $result['base_resp']['ret'] != 0) {
			
		switch ($data['ErrCode']) {
			case "-1":
				$msg = "系统错误，请稍候再试。";
				break;
			case "-2":
				$msg = "微信公众帐号或密码错误。";
				break;
			case "-3":
				$msg = "微信公众帐号密码错误，请重新输入。";
				break;
			case "-4":
				$msg = "不存在该微信公众帐户。";
				break;
			case "-5":
				$msg = "您的微信公众号目前处于访问受限状态。";
				break;
			case "-6":
				$msg = "登录受限制，需要输入验证码，稍后再试！";
				break;
			case "-7":
				$msg = "此微信公众号已绑定私人微信号，不可用于公众平台登录。";
				break;
			case "-8":
				$msg = "微信公众帐号登录邮箱已存在。";
				break;
			case "-200":
				$msg = "因您的微信公众号频繁提交虚假资料，该帐号被拒绝登录。";
				break;
			case "-94":
				$msg = "请使用微信公众帐号邮箱登陆。";
				break;
			case "10":
				$msg = "该公众会议号已经过期，无法再登录使用。";
				break;
			default:
				$msg = "未知的返回。";
		}
		echo $msg;
		return false;
			return false;
		}
        
		foreach ($snoopy->headers as $key => $value) {
			$value = trim($value);
			if(preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $value,$match))
				$cookie .=$match[1].'='.$match[2].'; ';
		}
		
		preg_match("/token=(\d+)/i",$result['redirect_url'],$matches);
		if($matches){
			$this->_token = $matches[1];
			$this->log('token:'.$this->_token);
		}
		$this->saveCookie($this->_cookiename,$cookie);
		return $cookie;
	}

	/**
	 * 把cookie写入缓存
	 * @param  string $filename 缓存文件名
	 * @param  string $content  文件内容
	 * @return bool
	 */
	public function saveCookie($filename,$content){
		return file_put_contents($filename,$content);
	}

	/**
	 * 读取cookie缓存内容
	 * @param  string $filename 缓存文件名
	 * @return string cookie
	 */
	public function getCookie($filename){
		if (file_exists($filename)) {
			$mtime = filemtime($filename);
			if ($mtime<time()-$this->_cookieexpired) 
				$data = '';
			else
				$data = file_get_contents($filename);
		} else
			$data = '';
		if($data){
			$send_snoopy = new Snoopy; 
			$send_snoopy->rawheaders['Cookie']= $data;
			$send_snoopy->maxredirs = 0;
			$url = "https://mp.weixin.qq.com/cgi-bin/indexpage?t=wxm-index&lang=zh_CN";
			$send_snoopy->fetch($url);
			$header = implode(',',$send_snoopy->headers);
			$this->log('header:'.print_r($send_snoopy->headers,true));
			preg_match("/token=(\d+)/i",$header,$matches);
			if(empty($matches)){
				return $this->login();
			}else{
				$this->_token = $matches[1];
				$this->log('token:'.$this->_token);
				return $data;
			}
		}else{
			return $this->login();
		}
	}

	/**
	 * 验证cookie的有效性
	 * @return bool
	 */
	public function checkValid()
	{
		if (!$this->cookie || !$this->_token) return false;
		$send_snoopy = new Snoopy; 
		$post = array('ajax'=>1,'token'=>$this->_token);
		$submit = "https://mp.weixin.qq.com/cgi-bin/getregions?id=1017&t=ajax-getregions&lang=zh_CN";
		$send_snoopy->rawheaders['Cookie']= $this->cookie;
		$send_snoopy->submit($submit,$post);
		$result = $send_snoopy->results;
		if(json_decode($result,1)){
			return true;
		}else{
			return false;
		}
	}
	
	private function log($log){
		if ($this->debug && function_exists($this->_logcallback)) {
			if (is_array($log)) $log = print_r($log,true);
			return call_user_func($this->_logcallback,$log);
		}
	}
	
}
