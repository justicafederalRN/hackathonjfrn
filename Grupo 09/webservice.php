<?php
include 'SQL.php';
if(!isset($_POST['args'])) die();

$sql = new SQL();
//var_dump(json_encode($sql->search(array('num' => '0802150-63.2017.4.05.8401'))));

echo json_encode($sql->search($_POST['args']));
?>
