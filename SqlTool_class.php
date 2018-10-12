<?php

	class SqlTool{
		private $conn;

    public function __construct(){
      $user = 'root';
			$password = 'root';
			$db = 'vote_information';
      $host = '193.112.134.138';
      // $host = '127.0.0.1';
      $port = 3306;
      // $host = '127.0.0.1';
      // $port = 8889;
      $this->conn = mysqli_connect(
        $host,
        $user,
        $password,
        $db,
        $port
      );
      if (empty($this->conn)){
        die('无法连接数据库服务器').mysqli_error($this->conn);
      }
      mysqli_query($this->conn, 'set names utf8');
    }
    public function execute_dql($sql){
      $res = mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
      return $res;
    }
    public function execute_dml($sql){
      $res = mysqli_query($this->conn, $sql) or die(mysqli_error($this->conn));
      // return $res;
      if(!$res){
        return 0;
      }else if(mysqli_affected_rows($this->conn)>0){
        return 1;
      }else{
        return 2;
      }
    }
//	分页
	//$sql1 : 选所有人
	//$sql2 : 有规则的选
	//&$fenyePage : 存储分页信息的一个对象
	public function execute_pages($sql1,$sql2,&$fenyePage){
		$res = mysql_query($sql1);
		if(mysql_num_rows($res)>0){
			$row = mysql_fetch_array($res);
			$fenyePage->rowCount = $row[0]; //总人数
			$fenyePage->pageCount = ceil($row[0]/$fenyePage->pageSize);
		}
		mysql_free_result($res);

		$res1 = mysql_query($sql2);
		$arr = array();
		if(mysql_num_rows($res1)>0){
			while($row1 = mysql_fetch_assoc($res1)){
				$arr[] = $row1;
			}
			$fenyePage->res_array = $arr;
		}
		mysql_free_result($res1);
	}



	public function close_connect(){
		if(empty($this->conn)){
			mysqli_close($this->conn);
		}
	}
}

?>
