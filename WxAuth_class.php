<?php
  //   include_once 'FenyePage_class.php';
	// include_once 'RankService_class.php';
	// $fenyePage = new fenyePage();
	// $rank = new RankService();
	// $rank->showRank($fenyePage);

	// $code=$_GET["code"];
	// $api="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
	// $json=httpGet($api);
	// //请求使用
	// $arr=json_decode($json,true);
	// $access_token=$arr["access_token"];
	// $openid=$arr["openid"];
	// $url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
	// $users=httpGet($url);
  // $user=json_decode($users,true);
  class WxAuth {
    private $appid = 'e99e3927216ddb4d44e2a01285d7fec0';
    private $appsecret = 'e99e3927216ddb4d44e2a01285d7fec0';

    public function getOpenId($code)
    {
      $APPID = $this->appid;
      $APPSECRET = $this->appsecret;
      // $api = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$APPSECRET&code=$code&grant_type=authorization_code";
      // $json = httpGet($api);
      // $arr = json_decode($json,true);
      // if (!$arr['openid']) {
      //   return $arr;
      // }
      // $access_token = $arr["access_token"];
      // $openid = $arr["openid"];
      $access_token = "lalalalalall";
      $openid = "admindml";
      return array(
        'access_token' => $access_token,
        'openid' => $openid
      );
    }
  }
?>
