<?php

class justicafacilDatabase {
    private $dbhost = "db_jf";
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

    public function listAssuntos(){

        $query = "SELECT DISTINCT assunto FROM processos ORDER BY assunto";

        return $this->banco->query($query);
  
    }

    public function searchClasses($classe){

        $query = "SELECT nrprocesso,parte_autora,parte_re,classe_processual FROM processos WHERE classe_processual LIKE '%{$classe}%' ORDER BY nrprocesso";

        return $this->banco->query($query);
  
    }

    public function searchAdvogado($advogado){

        $query = "SELECT * FROM processos WHERE advogado LIKE '%{$advogado}%'";

        return $this->banco->query($query);
  
    }

    public function searchMagistrado($magistrado,$parte_re,$pro_improcedente,$nrprocesso,$assunto){

        $query = "SELECT * FROM processos";

        if($nrprocesso != ''){
            $query = $query . " WHERE nrprocesso LIKE '%{$nrprocesso}%'";
            return $this->banco->query($query);
        }

        if($magistrado != ''){
            $query = $query . " WHERE magistrado LIKE '%{$magistrado}%'";
        } 

        if($parte_re != ''){
            if($magistrado === ''){
                $query = $query . " WHERE parte_re LIKE '%{$parte_re}%'";
            } else {
                $query = $query . " AND parte_re LIKE '%{$parte_re}%'";
            }
        }

        if($pro_improcedente != ''){
            if($parte_re === '' and $magistrado === ''){
                $query = $query . " WHERE pro_improcedente LIKE '%{$pro_improcedente}%'";
            } else {
                $query = $query . " AND pro_improcedente LIKE '%{$pro_improcedente}%'";
            }
        }

        echo "<script>console.log( 'QUERY: " . $assunto . "' );</script>";

        return $this->banco->query($query);

    }

    public function graficoProcesso($magistrado,$parte_re,$pro_improcedente,$nrprocesso){

        //$query = "SELECT pro_improcedente, COUNT(*) FROM processos group by pro_improcedente";
        $query = "SELECT pro_improcedente as tipos, COUNT(*) as quant FROM processos group by pro_improcedente";

        if($nrprocesso != ''){
            $query = $query . " WHERE nrprocesso LIKE '%{$nrprocesso}%'";

            return $this->banco->query($query);
        }

        if($magistrado != ''){
            $query = $query . " WHERE magistrado LIKE '%{$magistrado}%'";
        } 

        if($parte_re != ''){
            if($magistrado === ''){
                $query = $query . " WHERE parte_re LIKE '%{$parte_re}%'";
            } else {
                $query = $query . " AND parte_re LIKE '%{$parte_re}%'";
            }
        }

        if($pro_improcedente != ''){
            if($parte_re === '' and $magistrado === ''){
                $query = $query . " WHERE pro_improcedente LIKE '%{$pro_improcedente}%'";
            } else {
                $query = $query . " AND pro_improcedente LIKE '%{$pro_improcedente}%'";
            }
        }

        return $this->banco->query($query);

    }

}

?>
