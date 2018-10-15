<?php
	require_once 'SqlTool_class.php';
  require_once 'WxAuth_class.php';
  function getJson($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}
  session_start();
	// $openid = $_POST['openid'];
	// $user = $_POST['user'];
	// $pic = $_POST['pic'];
    // $score = $_POST['score'];
	// $type = $_POST['ty']; //方式，查询或是建排行榜


	// if($type == 'dml'){
	// 	$SqlTool1 = new SqlTool();
	// 	$sql1 = "select score from rank where openid = '$openid'";
	// 	$res1 = $SqlTool1->execute_dql($sql1);
	// 	if(mysql_num_rows($res1) == 1){
    //         $row = mysql_fetch_array($res1);
    //         $scoreB = $row[0];
    //         if($scoreB<$score){
    //         	$sql2 = "update rank set score='$score' where openid='$openid'";
    //             $res2 = $SqlTool1->execute_dml($sql2);
    //             if($res2 == 1){
    //                 echo '{"msg":"update"}';
    //             }
    //         }else{
    //             echo '{"msg":"no"}';
    //         }

	// 	}else if(!mysql_num_rows($res1)){
	// 		$sql3 = "insert into rank(openid,username,pic,score) values('$openid','$user','$pic','$score')";
	// 		$res3 = $SqlTool1->execute_dml($sql3);
    //         if($res3 == 1){
    //         	echo '{"msg":"insert"}';
    //         }
	// 	}
	// }else if($type == 'dql'){
	// 	$fenyePage = new fenyePage();
	// 	$rank = new RankService();
	// 	$rank->showRank($fenyePage);
    //     $str = $rank->execute_toJson($fenyePage);
    //     echo $str;
	// }
// INSERT INTO `vote_program`(`p_id`, `gold_type`, `name`) VALUES (1,0,"《我是勇敢小小兵》")
    class Curd{
      public function query_data ($sql) {
        $SqlTool = new SqlTool();
        $res = $SqlTool->execute_dql($sql);
        $array = $res -> fetch_all(MYSQLI_ASSOC);
        $SqlTool->close_connect();
        return $array;
      }

      public function query_count ($sql) {
        $SqlTool = new SqlTool();
        $res = $SqlTool->execute_dql($sql);
        $array = $res -> fetch_array();
        $SqlTool->close_connect();
        return $array;
      }

      public function dml_data ($sql) {
        $SqlTool = new SqlTool();
        $queryRes = $SqlTool1->execute_dql($sql1);
        $res = $SqlTool->execute_dml($sql);
        $SqlTool->close_connect();
        if ($res == 1) {
          return "";
        } elseif ($res == 0) {
          return "数据有误";
        } else {
          return "没有成功";
        }
      }
      public function getWxhahUserInfo($params)
      {
        $code = $params['code'];
        $appid = "wx3d87ebb7df88b56c";  
        $secret = "e99e3927216ddb4d44e2a01285d7fec0";  
        //第一步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2 = getJson($oauth2Url);

        $data = json_decode(file_get_contents("jssdk/access_token.json"));
        if ($data->expire_time < time()) {
          $o3url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
          $o3 = getJson($o3url);
          $access_token = $o3["access_token"];  
          $res = array();
          $res['expire_time'] = time() + 7000;
          $res['access_token'] = $access_token;
          $fp = fopen("jssdk/access_token.json", "w");
          fwrite($fp, json_encode($res));
          fclose($fp);
        } else {
          $access_token = $data->access_token;
        }
        
        //第二步:根据全局access_token和openid查询用户信息  
        // $access_token = $o3["access_token"];  
        $openid = $oauth2['openid'];  
        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo = getJson($get_user_info_url);
        
        //打印用户信息
         return $userinfo;
      }

      // 接口
      public function getScore($params) // 查询投票节目数据
      {
        $type = $params['type'];
        $voteType = $params['voteType'];
        // $sql = "select vote_program.* from vote_program left join vote_record on vote_program.p_id = vote_record.vote_id where gold_type = $type order by vote_program.id asc";
        $sql = "select t3.*,ifnull( t2.cnt,0) as count from (select vote_id, count(1) as cnt from vote_record t1 where t1.vote_type = $voteType group by vote_id) t2 right join vote_program t3 on t2.vote_id = t3.id where t3.gold_type = $type order by t3.id asc";
        $res = $this -> query_data($sql);
        $sql1 = "select count(1) as count from vote_record where vote_record.vote_type = $voteType";
        $res1 = $this -> query_count($sql1);
        if (empty($res)) {
          return array(
            'result' => 0,
            'content' => array(
              "list" => "[]",
              "totalCount" => 0
            )
          );
        } else {
          return array(
            'result' => 0,
            'content' => array(
              "list" => $res,
              "totalCount" => $res1['count']
            )
          );
        }
      }

      /**
       * @params type
       *         'vote'  投票
       *         'sign'  报名春晚
       *
       */
      public function getSysTime($params)
      {
        date_default_timezone_set("Asia/Shanghai"); 
        $nowTime = time();
        // $voteStartTime = '2018-10-14 19:00:00';
        $voteStartTime = '2018-10-14 19:00:00';
        $voteEndTime = '2018-10-14 20:00:00';
        $signTime = '2018-11-11 14:00:00';
        if ($params['type'] == 'vote') {
          $targetStartTime = strtotime($voteStartTime);
          $targetEndTime = strtotime($voteEndTime);
          return array(
            'result' => 0,
            'content' => array(
              'nowTime' => $nowTime,
              'startTime' => $targetStartTime,
              'endTime' => $targetEndTime
            )
          );
        } else {
          $targetEndTime = strtotime($signTime);
          return array(
            'result' => 0,
            'content' => array(
              'nowTime' => $nowTime,
              'endTime' => $targetEndTime,
            )
          );
        }
      }
      /**
       * @params code 微信code
       */
      public function login($params) // 登录，返回用户数据，包括是否已经投票
      {
        $SqlTool = new SqlTool();
        $wx = new WxAuth();
        $wxUserInfo = $this -> getWxhahUserInfo($params);
        if ($_SESSION['openId']) {
          // $wxUserInfo = $this -> getWxUserInfo();
          $resUserInfo = $this -> getUserInfo();
          if ($resUserInfo['result'] == 0) {
            return array(
              'result' => 0,
              'content' => array(
                'wxUserInfo' => $wxUserInfo,
                'recordInfo' => $resUserInfo['content']
              )
            );
          } else {
            return $resUserInfo;
          }
        }
        if (!$wxUserInfo['openid']) {
          return array(
            'result' => 90,
            'desc' => implode(" ",$wxUserInfo)
          );
        }
        $openId = $wxUserInfo['openid'];
        $sql_select = "select open_ID from user where open_ID = '$openId'";
        $result = $SqlTool->execute_dql($sql_select);
        if (mysqli_num_rows($result) == 1) {
          $SqlTool->close_connect();
          $_SESSION['openId'] = $openId;
          $resUserInfo = $this -> getUserInfo();
          if ($resUserInfo['result'] == 0) {
            return array(
              'result' => 0,
              'content' => array(
                'wxUserInfo' => $wxUserInfo,
                'recordInfo' => $resUserInfo['content']
              )
            );
          } else {
            return $resUserInfo;
          }
        } else if ($wxUserInfo['subscribe'] == 1) {
          $wxUserInfoStr = json_encode(array(
            'subscribe' => 1
          ));
          $sql = "insert into user(open_ID, wx_userinfo) values('$openId', '$wxUserInfoStr')";
          $res = $SqlTool->execute_dml($sql);
          $SqlTool->close_connect();
          if ($res == 1) {
            $_SESSION['openId'] = $openId;
            $resUserInfo = $this -> getUserInfo();
            if ($resUserInfo['result'] == 0) {
              return array(
                'result' => 0,
                'content' => array(
                  'wxUserInfo' => $wxUserInfo,
                  'recordInfo' => $resUserInfo['content']
                )
              );
            } else {
              return $resUserInfo;
            }
          } elseif ($res == 0) {
            return array(
              'result' => 89,
              'desc' => "数据有误"
            );
          } else {
            return array(
              'result' => 89,
              'desc' => "没有成功"
            );
          }
        }
      }

      /**
       * 获取user表信息
       */
      public function getWxUserInfo()
      {
        if (!isset($_SESSION['openId'])) {
          return array(
            'result' => 10,
            'desc' => "重新登录"
          );
        }
        $sql = "select * from user where open_ID = '$_SESSION[openId]'";
        $res = $this -> query_data($sql);
        if (count($res) > 0) {
          return array(
            'result' => 0,
            'content' => $res[0]
          );
        } else {
          return array(
            'result' => 0,
            'content' => "{}"
          );
        }
      }

      /**
       * 获取用户信息
       */
      public function getUserInfo()
      {
        if (!isset($_SESSION['openId'])) {
          return array(
            'result' => 10,
            'desc' => "重新登录"
          );
        }
        $sql = "select * from vote_record where open_ID = '$_SESSION[openId]'";
        $res = $this -> query_data($sql);
        if (count($res) > 0) {
          return array(
            'result' => 0,
            'content' => $res[0]
          );
        } else {
          return array(
            'result' => 0,
            'content' => "{}"
          );
        }
      }

      /**
       * @params voteId
       */
      public function voteProgram($params)
      {
        $SqlTool = new SqlTool();
        $voteId = $params['voteId'];
        $voteType = $params['voteType'];
        if (!isset($_SESSION['openId'])) {
          return array(
            'result' => 10,
            'desc' => "重新登录"
          );
        }
        $times = $this->getSysTime(array(
          'type' => 'vote'
        ));
        if ($times['result'] == 0) {
          if ($times['content']['nowTime'] < $times['content']['startTime']) {
            return array(
              'result' => 68,
              'desc' => "投票尚未开启"
            );
          }
          if ($times['content']['nowTime'] > $times['content']['endTime']) {
            return array(
              'result' => 69,
              'desc' => "投票已经结束"
            );
          }
        } else {
          return array(
            'result' => 64,
            'desc' => "获取时间失败"
          );
        }
        $openId = $_SESSION['openId'];
        $sql_select = "select * from vote_record where open_ID = '$openId'";
        $result = $this -> query_data($sql_select);
        if(count($result) == 1) {
          $SqlTool->close_connect();
          return array(
            'result' => 67,
            'desc' => "用户已经投过票"
          );
        } else {
          $voteTime = time();
          $sql = "insert into vote_record(open_ID, vote_time, vote_id, vote_type) values('$openId', '$voteTime', '$voteId', '$voteType')";
          $res = $SqlTool->execute_dml($sql);
          $SqlTool->close_connect();
          if ($res == 1) {
            return array(
              'result' => 0,
              'content' => ""
            );;
          } elseif ($res == 0) {
            return array(
              'result' => 66,
              'desc' => "数据有误"
            );
          } else {
            return array(
              'result' => 65,
              'desc' => "没有成功"
            );
          }
        }
      }

      public function getShare($params)
      {
        $wx = new WxAuth();
        $url = $params['url'];
        $res = $wx -> getSignPackage($url);
        if ($res['signature']) {
          return array(
            'result' => 0,
            'content' => $res
          );
        }
      }
    }
?>
