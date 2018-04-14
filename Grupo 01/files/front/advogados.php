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
</head>

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
        <li class="breadcrumb-item active">Advogados</li>
      </ol>

    <div class="col-lg-12">
      <form method="POST">
        <div class="row">
          <div class="col-lg-8">
          <div class="form-group">
            <input type="inputAdvogado" class="form-control" name="Categorias" id="Categorias" placeholder="Digite o nome do Advogado">
            <input type="submit" name="submit" value="Filter" />
          </div>
        </div><div class="col-lg-2">
          <div class="form-group">
            <input type="inputAdvogado" class="form-control" name="Categorias" id="Categorias" placeholder="Preencha a data">
            <input type="submit" name="submit" value="Filter" />
          </div>

        </div><div class="col-lg-2">
          <div class="form-group">
            <input type="inputAdvogado" class="form-control" name="Categorias" id="Categorias" placeholder="Digite a Categoria">
            <input type="submit" name="submit" value="Filter" />
          </div>
        </div>
        </div> 
      </form>
    </div>

        <div class="col-lg-12" style="margin: 0 0 10px 0;">

        <div class="card mb-3">
          <style type="text/css">
            #myChart{
              height: 200px!important;
            }
          </style>

          <canvas id="myChart" width="1024" style="height: 200px; margin: 0; "></canvas>
          <script>
            // Nossos rótulos para o eixo X
            var years = [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050];

            // Para desenhar as linhas
            var africa = [86,114,106,106,107,111,133,221,783,2478];
            var asia = [282,350,411,502,635,809,947,1402,3700,5267];
            var europe = [168,170,178,190,203,276,408,547,675,734];
            var latinAmerica = [40,20,10,16,24,38,74,167,508,784];
            var northAmerica = [6,3,2,2,7,26,82,172,312,433];

            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: years,
                datasets: [
                  { 
                    data: africa,
                    label: "Categoria"
                  }
                ]
              }
            });

          </script>

        </div>

        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Nº Processo</th>
                  <th>Advogado</th>
                  <th>Parte Autora</th>
                  <th>Parte Ré</th>
                  <th>Classe Processual</th>
                </tr>
              </thead>
              <tbody>
                  <?php

                    if ( isset($_POST['submit']) ) {

                      $classe_processual = utf8_decode($_POST['Categorias']);
                    
                      $resultado = $database->searchAdvogado($classe_processual);

                      while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>\n" . 
                        "<td>" . utf8_encode($row['nrprocesso']) . "</td>\n" .
                        "<td>" . utf8_encode($row['advogado']) . "</td>\n" .
                        "<td>" . utf8_encode($row['parte_autora']) . "</td>\n" .
                        "<td>" . utf8_encode($row['parte_re']) . "</td>\n" .
                        "<td>" . utf8_encode($row['classe_processual']) . "</td>\n" .
                        "</tr>\n";
                      }

                    }

                  ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Nº Processo</th>
                  <th>Advogado</th>
                  <th>Parte Autora</th>
                  <th>Parte Ré</th>
                  <th>Classe Processual</th>
                </tr>
              </tfoot>
              <tbody>
              </tbody>
            </table>
          </div>

        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>
    </div>

    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © Your Website 2018</small>
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