<?php
/**
 * 学生信息绑定模块处理程序
 *
 * @author yanson
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
include("wechatext.class.php");

class StuBindModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		$from_user=$this->message['from'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		
		global $_W;
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('stubind_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));

		//自动获取用户信息
		$options = array(
		'account'=> $row['account'] ,
		'password'=>$row['password'],
		'datapath'=>$_W['attachurl'].'cookie_',
		); 
		$wechat = new Wechatext($options);
		if ($wechat->checkValid()) {
			$topmsg = $wechat->getTopMsg();
			$msgid=$topmsg['id'];//信息ID
			$fakeid=$topmsg['fakeid'];//fakeid
			$nick_name = $topmsg['nick_name'];//姓名
			$userdata = $wechat->getInfo($fakeid);
			$signature = $userdata['signature'];//备注
			$country = $userdata['country'];//国家
			$province = $userdata['province'];//省份
			$city = $userdata['city'];//城市
			$gender = $userdata['gender'];//性别 1男 2女
			$getHeadImg=$wechat->getHeadImg($fakeid);
			$HeadImgpath='stubind/headimg/'.$fakeid.'.jpg';
			$isfile_write=file_write($HeadImgpath,$getHeadImg);
			

			$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
			$row_info = pdo_fetch($sql, array(':from_user' => $from_user));
			$insert = array(
								'weid' => $_W['weid'],
								'from_user'=>$this->message['from'],
								'fakeid'=>$fakeid,
								'wx_nickname'=>$nick_name,
								'signature' =>$signature,
								'country'=>$country,
								'province'=>$province,
								'city'=>$city,
								'avatar' => $HeadImgpath
						);				
			
			if (!empty($insert)) {
				foreach ($insert as $field => $value) {
					if (!isset($value)) {
						unset($insert[$field]);
					continue;
					}
				}
			}
			
			if (empty($row_info['realname'])) {
				if(empty($row_info['id'])){
				$id=pdo_insert('stu_profile', $insert);
				}
				$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
				$row_info = pdo_fetch($sql, array(':from_user' => $from_user));
				$response['FromUserName'] = $this->message['to'];
				$response['ToUserName'] = $this->message['from'];
				$response['MsgType'] = 'news';
				$response['ArticleCount'] = 1;
				$response['Articles'] = array();
				$response['Articles'][] = array(
						'Title' => $row['title'],
						'Description' => '戳进去进行【绑定】'. PHP_EOL ."微信名：" .$row_info['wx_nickname'] . PHP_EOL . $row_info['country'] . $row_info['province'].$row_info['city']. PHP_EOL ."签名:". $row_info['signature'] . PHP_EOL . PHP_EOL ."如果上面微信信息与你的不符合，请重新回复【绑定】",
						'PicUrl' => empty($row_info['avatar'])?'':$_W['attachurl'].$row_info['avatar'],
						'Url' => $_W['siteroot'] .$this->createMobileUrl('stubind', array('do' => 'stubind','name' => 'stubind','id' => $rid,'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))),
						'TagName' => 'item',
				);
			} else {
				pdo_update('stu_profile', $insert, array('from_user' => $from_user));
				$sql = "SELECT * FROM " . tablename('stu_profile') . " WHERE `from_user`=:from_user LIMIT 1";
				$row_info = pdo_fetch($sql, array(':from_user' => $from_user));
				$response['FromUserName'] = $this->message['to'];
				$response['ToUserName'] = $this->message['from'];
				$response['MsgType'] = 'news';
				$response['ArticleCount'] = 1;
				$response['Articles'] = array();
				$response['Articles'][] = array(
						'Title' => $row['title'],
						'Description' => '你【已经】绑定过了'. PHP_EOL .'戳进去【更新】或【解绑】'. PHP_EOL . PHP_EOL  ."微信名：" .$row_info['wx_nickname'] . PHP_EOL . $row_info['country'] . $row_info['province'].$row_info['city']. PHP_EOL ."签名:". $row_info['signature']. PHP_EOL . PHP_EOL ."如果上面微信信息与你的不符合，请重新回复【绑定】" ,
						'PicUrl' => empty($row_info['avatar'])?'':$_W['attachurl'].$row_info['avatar'],
						'Url' => $_W['siteroot'] .$this->createMobileUrl('stubind', array('do' => 'stubind','name' => 'stubind','id' => $rid,'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))),
						'TagName' => 'item',
				);	
			}
			
		
		
		}else{
		
		
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'news';
		$response['ArticleCount'] = 1;
		$response['Articles'] = array();
		$response['Articles'][] = array(
				'Title' => $row['title'],
				'Description' => $row['description'],
				'PicUrl' => empty($row['thumb'])?'':$_W['attachurl'].$row['thumb'],
				'Url' => $_W['siteroot'] .$this->createMobileUrl('stubind', array('do' => 'stubind','name' => 'stubind','id' => $rid,'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))),
				'TagName' => 'item',
		);
		}
		
		
		return $response;
	}
	

}