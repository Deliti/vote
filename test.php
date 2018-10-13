<?php
// public function getWxUserInfo($params)
// {
//   $code = $params['code'];
//   $appid = "wx3d87ebb7df88b56c";  
//   $secret = "e99e3927216ddb4d44e2a01285d7fec0";  
//   //第一步:取得openid
//   $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
//   $oauth2 = getJson($oauth2Url);
    
//   //第二步:根据全局access_token和openid查询用户信息  
//   $access_token = $oauth2["access_token"];  
//   $openid = $oauth2['openid'];  
//   $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
//   $userinfo = getJson($get_user_info_url);
  
//   //打印用户信息
//     return $userinfo;
// }
 

?>