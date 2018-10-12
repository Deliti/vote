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
    private $appid = 'wx3d87ebb7df88b56c';
    private $appsecret = 'e99e3927216ddb4d44e2a01285d7fec0';

    public function getOpenId($code)
    {
      $APPID = $this->appid;
      $APPSECRET = $this->appsecret;
      $api = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$APPSECRET&code=$code&grant_type=authorization_code";
      $json = $this -> http_curl($api);
      $arr = json_decode($json,true);
      if (!$arr['openid']) {
        return $arr;
      }
      $access_token = $arr["access_token"];
      $openid = $arr["openid"];
      return array(
        'access_token' => $access_token,
        'openid' => $openid
      );
    }

    public function http_curl($url){
      $curl = curl_init();
      //设置抓取的url
      curl_setopt($curl, CURLOPT_URL, $url);
      //设置头文件的信息作为数据流输出
      curl_setopt($curl, CURLOPT_HEADER, 0);
      //设置获取的信息以文件流的形式返回，而不是直接输出。
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      //执行命令
      $data = curl_exec($curl);
      //关闭URL请求
      curl_close($curl);
      //显示获得的数据
      return $data;
    }
  }
?>
