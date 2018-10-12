<?php
  require_once 'SqlTool_class.php';
  $SqlTool = new SqlTool();
  $openId = "whywhy";
  $voteTime = time();
  $voteId = 1;
  $sql = "select vote_program.* from vote_program left join vote_record on vote_program.p_id = vote_record.vote_id where gold_type = 1 order by vote_program.id asc";
  // $sql = "select open_ID from user where open_ID = zailaiyici";
  // $sql = "insert into vote_record(open_ID, vote_time, vote_id) values('$openId', '$voteTime', '$voteId')";
  $res = $SqlTool->execute_dql($sql);
  $array = $res -> fetch_all(MYSQLI_ASSOC);
  // $SqlTool = new SqlTool();
  //       $res = $SqlTool->execute_dql($sql);
  //       $array = $res -> fetch_all(MYSQLI_ASSOC);
  //       $SqlTool->close_connect();
  //       echo $array;

  //   $user = 'root';
  //   $password = 'root';
  //   $db = 'vote_information';
  //   $host = '127.0.0.1';
  //   $port = 8889;

  //   $conn = mysqli_connect(
  //     $host,
  //     $user,
  //     $password,
  //     $db,
  //     $port
  //   );
  //   echo '23';
  //   $sql1 = "select count(1) as count from vote_record where 1";
  //   $res1 = mysqli_query($conn, $sql) or die(mysqli_error());
  //   echo $res1;
  //   $res = mysqli_query($conn, $sql) or die(mysqli_error());
  //     // return $res;
  //     if(!$res){
  //       echo 0;
  //     }else if(mysqli_affected_rows($conn)>0){
  //       echo 1;
  //     }else{
  //       echo 2;
  //     }
  echo count($array);
?>
