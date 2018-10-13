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
  session_start();
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
      $_SESSION['firstToken'] = "access_token";
      print "firsttoken" . $access_token; 
      print "freshJson" . $json . "end";
      $refreshToken = $arr['refresh_token'];
      print "拿到的" . $refreshToken . "end";
      $access_token = $this -> getLoginAccessToken($refreshToken);
      print "sectoken" . $access_token;
      $_SESSION['freshToken'] = $access_token;
      $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
      $userinfo = $this -> http_curl($get_user_info_url);
      return array(
        'access_token' => $access_token,
        'openid' => $openid,
        'userinfo' => $userinfo
      );
    }

    public function http_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function post_curl($url, $params)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }

    public function getSignPackage($url)
    {
        $durl = urldecode($url);

        $jsapiTicket = $this->getJsApiTicket();
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$durl";


        $signature = sha1($string);


        $signPackage = [
            "appId" => $this->appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $durl,
            "signature" => $signature,
            "rawString" => $string
        ];
//        var_dump($signPackage);die;
        // throw new SuccessMessage(['msg' => $signPackage]);
        return $signPackage;
    }


    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getLoginAccessToken($access_token)
    {
        print "进来的freshToken" . $access_token;
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jssdk/login_token.json"));
        if ($data->expire_time < time()) {
            //定义传递的参数数组
            $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $this->appid . "&grant_type=refresh_token&refresh_token=" . $access_token;
            $res = json_decode($this -> http_curl($url, $params));
            $new_token = isset($res->refresh_token) ? $res->refresh_token : NULL;
            if ($new_token) {
                print "进来了" . $new_token;
                $res->expire_time = time() + 2505600;
                $fp = fopen("jssdk/login_token.json", "w");
                fwrite($fp, json_encode($res));
                fclose($fp);
                print "写好了" . file_get_contents("jssdk/login_token.json");
            }
        } else {
            $new_token = $data->access_token;
        }
        print "离开了" . $new_token;
        return $new_token;
    }


    private function getJsApiTicket()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jssdk/jsapi_ticket.json"));
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            //定义传递的参数数组
            $params = array();
            $params['type'] = 'jsapi';
            $params['access_token'] = $accessToken;
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $params['access_token'] . "&type=" . $params['type'] . "";
            $res = json_decode($this -> http_curl($url, $params), true);
            $ticket = isset($res['ticket']) ? $res['ticket'] : NULL;
            if ($ticket) {
                $res['expire_time'] = time() + 7000;
                $res['jsapi_ticket'] = $ticket;
                $fp = fopen("./jssdk/jsapi_ticket.json", "w");
                fwrite($fp, json_encode($res));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }
        return $ticket;
    }


    private function getAccessToken()
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jssdk/access_token.json"));
        if ($data->expire_time < time()) {
            //定义传递的参数数组
            $params = array();
            $params['grant_type'] = 'client_credential';
            $params['appid'] = $this->appid;
            $params['secret'] = $this->appsecret;
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=" . $params['grant_type'] . "&appid=" . $params['appid'] . "&secret=" . $params['secret'] . "";
            $res = json_decode($this -> http_curl($url, $params), true);
            $access_token = isset($res['access_token']) ? $res['access_token'] : NULL;
            if ($access_token) {
                $res['expire_time']= time() + 7000;
                $res['access_token'] = $access_token;
                $fp = fopen("./jssdk/access_token.json", "w");
                fwrite($fp, json_encode($res));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }
  }
?>