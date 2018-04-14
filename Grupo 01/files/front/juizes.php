<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Justiça Fácil</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <link type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  
</head>
<script type="text/javascript">
    $(document).ready(function() {
         
        // Captura o retorno do retornaCliente.php
        $.getJSON('retornaMagistrado.php', function(data){
            var cliente = [];
             
            // Armazena na array capturando somente o nome do cliente
            $(data).each(function(key, value) {
                cliente.push(value.cliente);
            });
             
            // Chamo o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
            $('#nomeMagistrado').autocomplete({ source: cliente, minLength: 3});
        });
    });
</script>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <?php include("db.php"); $database = new justicafacilDatabase();?>
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.php">Justiça Fácil</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="classe-processual.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Classe Processual</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="juizes.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Juizes</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="advogados.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Advogados</span>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-envelope"></i>
            <span class="d-lg-none">Messages
              <span class="badge badge-pill badge-primary">12 New</span>
            </span>
            <span class="indicator text-primary d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
          </a>
          <div class="dropdown-menu" aria-labelledby="messagesDropdown">
            <h6 class="dropdown-header">New Messages:</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>David Miller</strong>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">Hey there! This new version of SB Admin is pretty awesome! These messages clip off when they reach the end of the box so they don't overflow over to the sides!</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>Jane Smith</strong>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">I was wondering if you could meet for an appointment at 3:00 instead of 4:00. Thanks!</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <strong>John Doe</strong>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">I've sent the final files over to you for review. When you're able to sign off of them let me know and we can discuss distribution.</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item small" href="#">View all messages</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
            <span class="d-lg-none">Alerts
              <span class="badge badge-pill badge-warning">6 New</span>
            </span>
            <span class="indicator text-warning d-none d-lg-block">
              <i class="fa fa-fw fa-circle"></i>
            </span>
          </a>
          <div class="dropdown-menu" aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">New Alerts:</h6>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-danger">
                <strong>
                  <i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <span class="text-success">
                <strong>
                  <i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
              </span>
              <span class="small float-right text-muted">11:21 AM</span>
              <div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item small" href="#">View all alerts</a>
          </div>
        </li>
        <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for...">
              <span class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <!--<li class="breadcrumb-item">
          <a href="#">Classe Processual</a>
        </li>-->
        <li class="breadcrumb-item active">Juizes</li>
      </ol>

      <form method="POST">
        <div class="row">
          <div class="col-lg-3">
            <div class="form-group">
              <script>
                function formatar(formatoDaEntrada, input){
                var i = input.value.length;

                var saida = formatoDaEntrada.substring(0,1);
                var texto = formatoDaEntrada.substring(i)

                if (texto.substring(0,1) != saida){
                  input.value += texto.substring(0,1);
                }
              }
              </script>
              <label for="labelSentenca"><b>Nº Processo:</b></label>
              <input type="text2" class="form-control" name="numeroProcesso" maxlength="25" placeholder="Ex.: XXXXXXX-XX.XXXX.X.XX.XXXX" OnKeyPress="formatar('9999999-99.9999.9.99.9999', this)" >

            </div>
          </div>
          <div class="col-lg-5">
            <div class="form-group">
              <label for="labelParteRe"><b>Parte Ré:</b></label>
              <input type="text2" class="form-control" name="nomeParteRe" id="nomeParteRe" placeholder="Digite a Parte Ré">
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <label for="labelSentenca"><b>Sentença:</b></label>
              <select class="form-control" name="selectSetenca">
                <option selected value="">Todas</option>
                <option value="P">Procedente</option>
                <option value="I">Improcedente</option>
                <option value="-">Em andamento</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3">
            <div class="form-group">
              <label for="labelMagistrado"><b>Nome do Magistrado:</b></label>
              <input type="text" class="form-control" name="nomeMagistrado" id="nomeMagistrado" placeholder="Digite o nome do Magistrado">
              
            </div>
          </div>
          <div class="col-lg-5">
            <div class="form-group">

              <label for="labelAssunto"><b>Assunto:</b></label>
              <select class="custom-select d-block w-100" name="Assuntos" id="Assuntos">
                <option value="">Escolha um assunto...</option>
                <?php

                  $resultado = $database->listAssuntos();

                  while ($row = $resultado->fetch_assoc()) {
                      echo "<option value=\"" . utf8_encode($row['assunto']) . "\">" . utf8_encode($row['assunto']) . "</option>";
                  }

                ?>
              </select>
              
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <input style="margin-top: 31px;; background-color: #343a40!important; border-color: #343a40!important;" type="submit" name="submit" class="btn btn-primary upload" value="Filtrar">
            </div>
          </div>
        </div>
      </form>

    <div class="row">

      <div class="col-lg-6" style="margin: 0 0 10px 0;">
        <canvas id="myChart2"></canvas>
          <script>
          var ctx = document.getElementById("myChart2").getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                  datasets: [{
                      label: '# of Votes',
                      data: [12, 19, 3, 5, 2, 3],
                      backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                          'rgba(54, 162, 235, 0.2)',
                          'rgba(255, 206, 86, 0.2)',
                          'rgba(75, 192, 192, 0.2)',
                          'rgba(153, 102, 255, 0.2)',
                          'rgba(255, 159, 64, 0.2)'
                      ],
                      borderColor: [
                          'rgba(255,99,132,1)',
                          'rgba(54, 162, 235, 1)',
                          'rgba(255, 206, 86, 1)',
                          'rgba(75, 192, 192, 1)',
                          'rgba(153, 102, 255, 1)',
                          'rgba(255, 159, 64, 1)'
                      ],
                      borderWidth: 1
                  }]
              },
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero:true
                          }
                      }]
                  }
              }
          });
          </script>
        </div>

      <div class="col-lg-6" style="margin: 0 0 10px 0;">
        <canvas id="myChart3"></canvas>
          <?php 
            $array=array
            (
                '0' => array
                    (
                        'product' => '4387'
                    ),
                '1' => array
                    (
                        'product' => '5421'
                    ),
                '2' => array
                    (
                        'product' => '52038'
                    )
            );

          ?>


          <script>
            var data=[];
          
          <?php

          if ( isset($_POST['submit']) ) {

              $magistrado = utf8_decode($_POST['nomeMagistrado']);
              $parte_re = utf8_decode($_POST['nomeParteRe']);
              $pro_improcedente = $_POST['selectSetenca'];
              $nrprocesso = $_POST['numeroProcesso'];

              $grafico = $database->graficoProcesso($magistrado,$parte_re,$pro_improcedente,$nrprocesso);

            if( $magistrado === '' and $parte_re === '' and  $pro_improcedente === '' and  $nrprocesso === '' ){
              //echo "alert('Preencha pelo menos um dos campos 2');";
            } else {
              $grafico = $database->graficoProcesso($magistrado,$parte_re,$pro_improcedente,$nrprocesso);
            }

            //$row = mysql_query("SELECT * pro_improcedente FROM processos WHERE nrprocesso LIKE $nrprocesso", );
            //$row_procedente = mysql_query("SELECT pro_improcedente WHERE pro_improcedente = "P" FROM processos WHERE nrprocesso LIKE $nrprocesso", );
            //$num_row = mysqli_fecth_row($row); <- quantidade total de linhas em pro_improcedente

            //quantidade em processo - 2 / num_row 
            //4 / num_row
            //4 / num_row 

            //ex quant de improcedente = 10
            //   total = 20
            //   porcentagem de improcedente  = (10/20);

            echo $num_row;

            foreach($grafico as $tem)
            {

           ?>;


        
          <?php }

          }

          ?>
          var ctx = document.getElementById("myChart3").getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: ["Improcedente", "Procedente", "Sem julgamento"],
                  datasets: [{
                      label: '# of Votes',
                      data: data,
                      backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                          'rgba(54, 162, 235, 0.2)',
                          'rgba(255, 206, 86, 0.2)'
                      ],
                      borderColor: [
                          'rgba(255,99,132,1)',
                          'rgba(54, 162, 235, 1)',
                          'rgba(255, 206, 86, 1)'
                      ],
                      borderWidth: 1
                  }]
              },
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero:true
                          }
                      }]
                  }
              }
          });
          </script>

    </div>


      <div class="col-lg-12" style="margin: 0 0 10px 0;">

        <div class="card mb-3">
          <style type="text/css">
            #myChart{
              height: 200px!important;
            }
          </style>

          

        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Nº Processo</th>
                  <th>Magistrado</th>
                  <th>Parte Autora</th>
                  <th>Parte Ré</th>
                  <th>Assunto</th>
                  <th>Sentença</th>
                </tr>
              </thead>
              <tbody>
                  <?php

                    if ( isset($_POST['submit']) ) {

                      $magistrado = utf8_decode($_POST['nomeMagistrado']);
                      $parte_re = utf8_decode($_POST['nomeParteRe']);
                      $pro_improcedente = $_POST['selectSetenca'];
                      $nrprocesso = $_POST['numeroProcesso'];
                      $assunto = $_POST['Assuntos'];

                      if( $magistrado === '' and $parte_re === '' and  $pro_improcedente === '' and  $nrprocesso === '' and  $assunto === '' ){
                        echo "<script>alert('Preencha pelo menos um dos campos');</script>";
                      } else {

                        $resultado = $database->searchMagistrado($magistrado,$parte_re,$pro_improcedente,$nrprocesso,$assunto);

                        while ($row = $resultado->fetch_assoc()) {
                          echo "<tr>\n"; 
                          echo "<td><a href=\"http://localhost/peticoes/" . utf8_encode($row['nrprocesso']) . ".pdf\">" . utf8_encode($row['nrprocesso']) . "</a></td>\n";
                          echo "<td>" . utf8_encode($row['magistrado']) . "</td>\n";
                          echo "<td>" . utf8_encode($row['parte_autora']) . "</td>\n";
                          echo "<td>" . utf8_encode($row['parte_re']) . "</td>\n";
                          echo "<td>" . utf8_encode($row['assunto']) . "</td>\n";

                          if (utf8_encode($row['pro_improcedente']) == "P"){

                            echo "<td><a href=\"http://localhost/sentenca/" . utf8_encode($row['nrprocesso']) . ".pdf\"> <span class=\"badge badge-success\">PROCEDENTE</span> </a></td>\n";

                          } elseif (utf8_encode($row['pro_improcedente']) == "I"){

                            echo "<td><a href=\"http://localhost/sentenca/" . utf8_encode($row['nrprocesso']) . ".pdf\"> <span class=\"badge badge-danger\">IMPROCEDENTE</span> </a></td>\n";

                          } else {

                            echo "<td><span class=\"badge badge-default\">EM ANDAMENTO</span></td>\n";

                          }

                          echo "</tr>\n";
                        }
                      }

                    }

                  ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Nº Processo</th>
                  <th>Magistrado</th>
                  <th>Parte Autora</th>
                  <th>Parte Ré</th>
                  <th>Classe Processual</th>
                  <th>Sentença</th>
                </tr>
              </tfoot>
              <tbody>
              </tbody>
            </table>
          </div>

        </div>
        
      </div>
    </div>

    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © SEI LA BOY 2018</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script src="js/sb-admin-charts.min.js"></script>
  </div>
</body>

</html>