<?php
/**
 * 小九处理类
 *
 * by：wzwjxgz
 */
$content = $this->message['content'];

		$this->beginContext(1800);
		$this->refreshContext(60);
		if($content=='退出' || $content=='取消'){
		$this->endContext();
		return $this->respText("你已经退出本模式");
		}
		global $_W;
		$content = xiaojo($this->message['content'],$this->message['from']);
		if(strstr($content,'#murl|')){
			$content = str_replace('#murl|','#musicurl|',$content);
			$content = str_replace('#hqurl|','#HQMusicUrl|',$content);
			$music = array();
			foreach (explode('#',$content) as $content) {
				list($k,$v)=explode('|',$content);
				$music[$k]=$v;
			}
			return $this->respMusic($music);
		}
		elseif(strstr($content,'#pic|')) {
			$content = str_replace('#pic|','#picurl|',$content);
			$news = array();
			if(strstr($content,'@title|')) {
				$content = str_replace('@title|','@titletitle|',$content);
				$a=array();
				$b=array();
				$n=0;
				$contents = $content;
				foreach (explode('@title',$content) as $b[$n]) {
					foreach (explode('#',$b[$n]) as $content) {
						list($k,$v)=explode('|',$content);
						$a[$k]=$v;
					}
					$news[$n] = $a;
					$n++;
				}
			} else {
				foreach (explode('#',$content) as $content) {
					list($k,$v)=explode('|',$content);
					$news[$k]=$v;
				}
			}
			return $this->respNews($news);
		}
		return $this->respText($content);


	function xiaojo($msg,$openid) {
		$yourdb='ourstudio';  //小九帐号
		$yourpw='ourstudio514';  //小九密码
		$msg=urlencode($msg);
		$openid=urlencode($openid);
		$yourdb=urlencode($yourdb);
		$url = 'http://www.xiaojo.com/api5.php?chat='.$msg.'&db='.$yourdb.'&pw='.$yourpw.'&from='.$openid;
		$response = ihttp_request($url);
		$reply = urldecode($response['content']);
		return $reply;
	}

