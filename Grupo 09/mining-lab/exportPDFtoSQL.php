<?php

include 'SQLSeeder.php';
include '../vendor/autoload.php';

class exportPDFtoSQL{
  private $path;
  private $obj = array();
  private $exec_time;

  function __construct($path){
    $temp = time();
    $this->path = $path;
    $this->exec_time = time() - $temp;
  }

  public function doConvert(){
    //Parse PDF to Array
    $parser = new \Wrseward\PdfParser\Pdf\PdfToTextParser('/usr/bin/pdftotext');
    $parser->parse($this->path);
    $data = explode("\n",$parser->text());
    foreach($data as $key => $val){
      if(strlen($val) <= 1)
        unset($data[$key]);
    }
    $temp = explode(' - ',$data[2]);
    $this->obj['num'] = explode(' ',$temp[0])[1];
    $this->obj['assunto'] = $temp[1];

    $ext = pathinfo($this->path, PATHINFO_EXTENSION);
    if($ext == 'pdf' || $ext == 'csv'){
      return $this->obj;
    }
    //Alimenta o banco de dados com as informações acima
    //$sql = new SQLSeeder($this->obj);
    //$sql->doSeed();
  }

  public function doExport(){
    return $this->obj;
  }

  public function doJSONExport(){
    return json_encode($this->obj);
  }

  public function getExecTime(){
    return $this->exec_time;
  }
}
?>
