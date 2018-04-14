<?php

class SQLSeeder {
  private $host = '127.0.0.1';
  private $port = '3306';
  private $user = 'root';
  private $pwd  = 'cdavid14';
  private $db   = 'processos';

  private $obj;

  public function doSeed($obj){
    // Conecta-se ao banco de dados MySQL
    $mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->db, $this->port);
    // Caso algo tenha dado errado, exibe uma mensagem de erro
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    //Checa se o processo já existe
    $check = $mysqli->query("SELECT COUNT(num) as cnt FROM processos WHERE num = '{$obj->num}';");
    if($check->fetch_field()->cnt > 0) trigger_error('Processo já existente no banco de dados');
    //Alimenta a query com as informações passadas pelo conversor
    $query = 'INSERT INTO projetos (num,classe_proc,assunto,advogado,vara,magistrado_na_dist,parte_aut,parte_re,ano,estado,procedente,tutela,vara,secao) VALUES (';
    foreach($obj as $val){
      $query .= "'$val',";
    }
    $query = rtrim($query,",");
    $query .= ');';
    $insert = $mysqli->query($query);
    echo 'Registros afetados: ' . $insert->affected_rows;
  }
}
 ?>
