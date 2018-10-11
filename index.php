<?php
require_once 'curd.php';
$curd = new Curd();
$cmdName = $_POST['cmd'];
$params = $_POST['params'];
$cmdConf = (Object) [
  'login' => array(
    'type' => 'dml'
  ),
  'getScore' => array(
    'type' => 'dql',
    'sql' => "select * from CHARACTER_SETS" // 查询得票数
  )
];
header('Content-Type:application/json');
$res = "";
// if (property_exists($cmdConf, $cmdName)) {
//   if ($cmdConf -> $cmdName['type'] == 'dql') {
//     $res = $curd -> query_data($cmdConf -> $cmdName['sql']);
//   } else {
//     $res = $curd -> dml_data($cmdConf -> $cmdName['sql']);
//   };
// }

$res = $curd -> $cmdName($params);
if ($res['result'] == 0) {
  $raw_success = array('result' => 0, 'content' => $res['content'], 'desc' => '请求成功');
  $res_success = json_encode($raw_success);
  echo $res_success;
} else {
  $raw_fail = array('result' => $res['result'], 'content' => "", 'desc' => $res['desc']);
  $res_fail = json_encode($raw_fail);
  echo $res_fail;
}
?>
