<?php

/*include 'exportPDFtoSQL.php';

$dir = '/media/christian/9919d60d-db8b-49b7-9f37-729e19efadd5/peticao/';
$time = date('Y:m:d h:i:s');
$dirs = scandir($dir);
$data = array();
//foreach($dirs as $val){
for($i=0;$i<count($dirs);$i++){
  $temp = new exportPDFtoSQL('/media/christian/9919d60d-db8b-49b7-9f37-729e19efadd5/peticao/'.$dirs[$i]);
  array_push($data,$temp->doConvert());
}

echo json_encode($data);
*/


?>
