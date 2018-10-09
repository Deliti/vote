<?php
require_once 'curd.php';
$curd = new Curd();
$cmdName = $_POST['cmd'];
header('Content-Type:application/json');
$res = "not";
switch ($cmdName) {
    case 'getScore':
        $res = $curd -> query_data('getScore');
        break;
    default :
        break;
}

$raw_success = array('result' => 0, 'content' => $res);
$res_success = json_encode($raw_success);
echo $res_success;
?>