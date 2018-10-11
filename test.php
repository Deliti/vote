<?php
  require_once 'SqlTool_class.php';
  $SqlTool = new SqlTool();
  $openId = 'whywhy';
  // $sql = "select * from user where open_ID = $openId";
  $sql = "select open_ID from user where open_ID = zailaiyici";
  // $sql = "insert into user(open_ID) values($openId)";
  $res = $SqlTool->execute_dql($sql);
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
  echo $res;
?>
