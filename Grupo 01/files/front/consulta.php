<?php

//$teste=$argv[1];

class justicafacilDatabase {
    private $dbhost = "172.29.0.2";
    private $dbuser = "jf";
    private $dbpass = "Q8ScP6Pjq3NjatXz";
    private $dbname = "justicafacil";
    public $banco;

    public function __construct()
    {
        $this->banco = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
    }

    public function __destruct()
    {
        $this->banco->close();
    }

    public function listClasses(){

        $query = "SELECT DISTINCT classe_processual FROM processos ORDER BY classe_processual";

        return $this->banco->query($query);
  
    }

    public function searchClasses($classe){

        $query = "SELECT nrprocesso,parte_autora,parte_re,classe_processual FROM processos WHERE classe_processual LIKE '%{$classe}%' ORDER BY nrprocesso";

        echo $query;

        $retorno = $this->banco->query($query);

        //echo $retorno;

        return $retorno;
  
    }

    public function searchAdvogado($advogado){

        $query = "SELECT * FROM processos WHERE advogado LIKE '%{$advogado}%'";

        return $this->banco->query($query);
  
    }

    public function searchMagistrado($magistrado){

        $query = "SELECT * FROM processos WHERE magistrado LIKE '%{$magistrado}%'";

        return $this->banco->query($query);

    }

}

$database = new justicafacilDatabase();

$resultado = $database->searchClasses("PROCEDIMENTO COMUM");

while ($row = $resultado->fetch_assoc()) {
  echo "<tr>\n" . 
  "<td>" . $row['nrprocesso'] . "</td>\n" .
  "<td>" . $row['parte_autora'] . "</td>\n" .
  "<td>" . $row['parte_re'] . "</td>\n" .
  "<td>" . $row['classe_processual'] . "</td>\n" .
  "</tr>\n";
}


?>