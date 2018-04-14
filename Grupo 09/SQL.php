<?php

class SQL{
  private $host = '127.0.0.1';
  private $port = '3306';
  private $user = 'root';
  private $pwd  = 'cdavid14';
  private $db   = 'ligadajustica';

  function __construct(){}

  function search($args){
    // Conecta-se ao banco de dados MySQL
    $mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->db, $this->port);
    // Caso algo tenha dado errado, exibe uma mensagem de erro
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    //SQL Constructor
    $sql = "SELECT * FROM processos";
    if(count($args) > 0){
      $sql .= " WHERE ";
      $i = 0;
      $len = count($args);
      foreach($args as $key => $val){
        if($i == $len - 1){
          $sql .= "{$key} = '{$val}'";
        }else{
          $sql .= "{$key} = '{$val}' AND";
        }
      }
    }
    $sql .= " LIMIT 100;";
    //Retrieve data
    $query = $mysqli->query($sql);
    $data = array();

    while($row = $query->fetch_array(MYSQLI_ASSOC)){
      $temp = $row;
      foreach($temp as $key => $val){
        $temp[$key] = utf8_encode($val);
      }
      $data[] = $temp;
    }

    /* close connection */
    $mysqli->close();

    return $data;
  }
}

?>
