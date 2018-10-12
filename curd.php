<?php
	require_once 'SqlTool_class.php';
  require_once 'WxAuth_class.php';
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

      // 接口
      public function getScore($params) // 查询投票节目数据
      {
        $type = $params['type'];
        $voteType = $params['voteType'];
        // $sql = "select vote_program.* from vote_program left join vote_record on vote_program.p_id = vote_record.vote_id where gold_type = $type order by vote_program.id asc";
        $sql = "select t3.*,ifnull( t2.cnt,0) as count from (select vote_id, count(1) as cnt from vote_record t1 where t1.vote_type = $voteType group by vote_id) t2 right join vote_program t3 on t2.vote_id = t3.id where t3.gold_type = $type order by t3.id asc";
        $res = $this -> query_data($sql);
        $sql1 = "select count(1) as count from vote_record where 1";
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
        $nowTime = time();
        // $voteStartTime = '2018-10-14 20:00:00';
        $voteStartTime = '2018-9-14 20:00:00';
        $voteEndTime = '2018-10-14 21:00:00';
        $signTime = '2018-11-11 21:00:00';
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
        $wxRes = $wx -> getOpenId($params['code']);
        if (!$wxRes['openid']) {
          return array(
            'result' => 90,
            'desc' => implode(" ",$wxRes)
          );
        }
        $openId = $wxRes['openid'];
        $sql_select = "select open_ID from user where open_ID = '$openId'";
        $result = $SqlTool->execute_dql($sql_select);
        if (mysqli_num_rows($result) == 1) {
          $SqlTool->close_connect();
          $_SESSION['openId'] = $openId;
          $resUserInfo = $this -> getUserInfo();
          if ($resUserInfo['result'] == 0) {
            return array(
              'result' => 0,
              'content' => $resUserInfo['content']
            );
          } else {
            return $resUserInfo;
          }
        } else {
          $sql = "insert into user(open_ID) values('$openId')";
          $res = $SqlTool->execute_dml($sql);
          $SqlTool->close_connect();
          if ($res == 1) {
            $_SESSION['openId'] = $openId;
            $resUserInfo = $this -> getUserInfo();
            if ($resUserInfo['result'] == 0) {
              return array(
                'result' => 0,
                'content' => $resUserInfo['content']
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
    }
?>
