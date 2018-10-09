<?php
	// require_once 'SqlTool_class.php';
	
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
	
    class Curd{

        public function query_data ($sql) {
            return "ok";
        }
    }
?>