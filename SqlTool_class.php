<?php

	class SqlTool{
		private $conn;
		private $db = SAE_MYSQL_DB;
		private $host= SAE_MYSQL_HOST_M;
        private $dk = SAE_MYSQL_PORT;
		private $username = SAE_MYSQL_USER;
		private $password = SAE_MYSQL_PASS;
	
	public function __construct(){
        $this->conn = mysql_connect($this->host.":".$this->dk,$this->username,$this->password);
		if(empty($this->conn)){
			die(mysql_error());
		}
		mysql_select_db($this->db,$this->conn);
		mysql_query("set names utf8");
	}
	public function execute_dql($sql){
		$res = mysql_query($sql) or die(mysql_error());
		return $res;
	}
	public function execute_dml($sql){
		$res = mysql_query($sql) or mysql_error();
		if(!$res){
			return 0;
		}else if(mysql_affected_rows($this->conn)>0){
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
			mysql_close($this->conn);
		}
	}
}

?>