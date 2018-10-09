<?php
    include_once 'FenyePage_class.php';
	include_once 'RankService_class.php';
	$fenyePage = new fenyePage();
	$rank = new RankService();
	$rank->showRank($fenyePage);
	 
	$code=$_GET["code"];
	$api="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
	$json=httpGet($api);
	//请求使用
	$arr=json_decode($json,true);
	$access_token=$arr["access_token"];
	$openid=$arr["openid"];
	$url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
	$users=httpGet($url);
	$user=json_decode($users,true);
?>