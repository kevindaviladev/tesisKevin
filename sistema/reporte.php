<?php
require_once("php/conexion.php");
$id = $_GET['id'];
$cantidad = 0;
$riesgo = "XD";
$contadorBajo;
$contadorNormal;
$contadorAlto;
$contadorCritico;
$acumuladorAlimentacion = 0;
$acumuladorGenetica = 0;
$acumuladorGlucosa = 0;
$acumuladorActividadFisica = 0;
$promedioAlimentacion;
$promedioGenetica;
$promedioGlucosa;
$promedioActividadFisica;

$sql = "select nombre from test where id=$id";
$result = $cnx->query($sql) or die("error");
if($reg=$result->fetchObject()){
    $nombre = $reg->nombre;
};

$sql = "select count(*) as cantidad from estudiante_test where id_test=$id;";
$result = $cnx->query($sql) or die("error");

if($reg=$result->fetchObject()){
    $cantidad = $reg->cantidad;
    // echo 'Cantidad de participantes: '.$reg->cantidad;
};

$sql = "select resultado from estudiante_test where id_test=$id";
$result = $cnx->query($sql) or die("error");
while($reg=$result->fetchObject()){
    $resultado = $reg->resultado;
    if($resultado>=0 && $resultado<0.1){
        // alert("RIESGO BAJO");
        $contadorBajo++;
    }

    if($resultado>=0.1 && $resultado<0.25){
        // alert("RIESGO NORMAL");
        $contadorNormal++;
    }

    if($resultado>=0.25 && $resultado<0.55 ){
        // alert("RIESGO ALTO");
        $contadorAlto++;
    }

    if($resultado>=0.55 && $resultado<=1){
        // alert("RIESGO CRITICO");
        $contadorCritico++;
    }
};

$sql = "select etv.* from estudiante_test_variable etv
inner join estudiante_test et on etv.id_test_estudiante = et.id
where et.id_test=$id";
$result = $cnx->query($sql) or die("error");
while($reg=$result->fetchObject()){
    // echo 'IdVarible: '.$reg->id_variable.'.   ';
    // echo 'IdVarible: '.$reg->id_variable.'Resultado: '.$reg->resultado.'</br>';
    if($reg->id_variable==1){
        $acumuladorAlimentacion = $acumuladorAlimentacion + $reg->resultado;
        // echo 'Acumulador Alimentacion: '.$acumuladorAlimentacion.'</br>';
    }
    if($reg->id_variable==2){
        $acumuladorGenetica = $acumuladorGenetica + $reg->resultado;
    }
    if($reg->id_variable==3){
        $acumuladorGlucosa = $acumuladorGlucosa + $reg->resultado;
    }
    if($reg->id_variable==4){
        $acumuladorActividadFisica = $acumuladorActividadFisica + $reg->resultado;
    }
};

$promedioAlimentacion = $acumuladorAlimentacion/$cantidad;
$promedioGenetica = $acumuladorGenetica/$cantidad;
$promedioGlucosa = $acumuladorGlucosa/$cantidad;
$promedioActividadFisica = $acumuladorActividadFisica/$cantidad;
// echo $promedioAlimentacion.' - '.$promedioGenetica.' - '.$promedioGlucosa.' - '.$promedioActividadFisica;

$porcentajeAlimentacion = $promedioAlimentacion*100/7;
$porcentajeGenetica = $promedioGenetica*100/5;
$porcentajeGlucosa = $promedioGlucosa*100/140;
$porcentajeActividadFisica = $promedioActividadFisica*100/7;
// echo $porcentajeAlimentacion.' - '.$porcentajeGenetica.' - '.$porcentajeGlucosa.' - '.$porcentajeActividadFisica;
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Lista de test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- modernizr css -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>

    <!-- Funciones -->
    <script src="./js/raphael-min.js"></script>
    <script src="./js/presentation.js"></script>
    <script src="./js/fuzzy-min.js"></script>


        <!-- ReportePIE -->
    <!-- <script src="./js/Chart.min.js"></script> -->
	<!-- <script src="../sistema/js/Chart.min.js"></script> -->
    <script src="./js/utils.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <style>
      [class$="-legend"] {
        list-style: none;
        cursor: pointer;
        padding-left: 0;
        text-align: center;
      }

      [class$="-legend"] li {
        display: inline-block;
        padding: 0 5px;
        text-align: center;
      }

      [class$="-legend"] li.hidden {
        text-decoration: line-through;
        text-align: center;
      }

      [class$="-legend"] li span {
        border-radius: 5px;
        display: inline-block;
        height: 10px;
        margin-right: 10px;
        width: 10px;
        text-align: center;
      }
    </style>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="index.html"><img src="assets/images/icon/logo.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="active">
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-table"></i>
                                    <span>Examen</span></a>
                                <ul class="collapse">
                                    <li class="active"><a href="listaTest.php">Lista de Test</a></li>
                                    <li><a href="test.php">Test</a></li>

                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-table"></i>
                                    <span>Variable</span></a>
                                <ul class="collapse">
                                    <li><a href="listaVariable.php">Lista de variables</a></li>
                                    <li><a href="graficoVariable.php">Gráfico variables</a></li>

                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        
                    </div>
                    <!-- profile info & task notification -->
                    
                </div>
            </div>
            <!-- header area end -->
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">REPORTE DE TEST "<?php echo $nombre?>"</h4>
                            <!-- <ul class="breadcrumbs pull-left">
                                <li><a href="index.html">Variables</a></li>
                                <li><span>Test Diabetes</span></li>
                            </ul> -->
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="assets/images/author/logo.jpg" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown">Administrador </h4>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="row">
                    <!-- seo fact area start -->
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-6 mt-5 mb-3">
                                <div class="card">
                                    <div class="seo-fact sbg1">
                                    <?php
                                        // echo $id;


                                        $sql = "select sum(resultado)/count(*) as promedio_general from estudiante_test where id_test=$id;";
                                        $result = $cnx->query($sql) or die("error");

                                        if($reg=$result->fetchObject()){
                                            // echo 'Riesgo general: '.$reg->promedio_general;
                                            $riesgo = $reg->promedio_general;
                                            if($riesgo>=0 && $riesgo<0.1){
                                                // alert("RIESGO BAJO");
                                                $riesgo = "BAJO";
                                            }
                            
                                            if($riesgo>=0.1 && $riesgo<0.25){
                                                // alert("RIESGO NORMAL");
                                                $riesgo = "NORMAL";
                                            }
                            
                                            if($riesgo>=0.25 && $riesgo<0.55 ){
                                                // alert("RIESGO ALTO");
                                                $riesgo = "ALTO";
                                            }
                            
                                            if($riesgo>=0.55 && $riesgo<=1){
                                                // alert("RIESGO CRITICO");
                                                $riesgo = "CRITICO";
                                            }
                                        }
                                    ?>
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon">N PARTICIPANTES:</div>
                                            <h2><?php echo $cantidad?></h2>
                                        </div>
                                        <canvas id="seolinechart1" height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-md-5 mb-3">
                                <div class="card">
                                    <div class="seo-fact sbg2">
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon"> RESULTADO GENERAL:  </div>
                                            <h2><?php echo $riesgo?></h2>
                                        </div>
                                        <canvas id="seolinechart2" height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6 mb-3 mb-lg-0">
                                <div class="card">
                                    <div class="seo-fact sbg3">
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon">Variantes</div>
                                            <canvas id="seolinechart3" height="60"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="seo-fact sbg4">
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon">Comparación</div>
                                            <canvas id="seolinechart4" height="60"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!-- seo fact area end -->
                    <div class="clearfix"></div>

                    <div id="contenedor" class="col-lg-6" style="width:60%;">
                        <canvas id="chart-area" style="background-color:#ffffff"></canvas>
                        <div id="legend" style="background-color:#ffffff"></div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">PORCENTAJES</h4>
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table table-hover progress-table text-center">
                                            <thead class="text-uppercase">
                                                <tr>

                                                    <th scope="col">VARIBALE</th>
                                                    <th scope="col">PROMEDIO</th>
                                                    <th scope="col">GRAVEDAD</th>
                                                    <th scope="col">ESTADO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">ALIMENTACION</th>
                                                    <td><?php echo $promedioAlimentacion?></td>
                                                    <td>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentajeAlimentacion?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <?php
                                                        if($promedioAlimentacion>0 && $promedioAlimentacion<1){
                                                            echo '<td><span class="status-p bg-warning">Nada</span></td>';
                                                        };
                                                        if($promedioAlimentacion>=1 && $promedioAlimentacion<=3){
                                                            echo '<td><span class="status-p bg-warning">Poco</span></td>';
                                                        };
                                                        if($promedioAlimentacion>3 && $promedioAlimentacion<7){
                                                            echo '<td><span class="status-p bg-success">Mucho</span></td>';
                                                        };
                                                    ?>

                                                </tr>

                                                <tr>
                                                    <th scope="row">GENÉTICA</th>
                                                    <td><?php echo $promedioGenetica?></td>
                                                    <td>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentajeGenetica?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <?php
                                                        if($promedioGenetica>0 && $promedioGenetica<=1){
                                                            echo '<td><span class="status-p bg-success">Ninguno</span></td>';
                                                        };
                                                        if($promedioGenetica>1 && $promedioGenetica<=3){
                                                            echo '<td><span class="status-p bg-primary">Leve</span></td>';
                                                        };
                                                        if($promedioGenetica>3 & $promedioGenetica<=5){
                                                            echo '<td><span class="status-p bg-warning">Grave</span></td>';
                                                        };
                                                    ?>
                                                </tr>

                                                <tr>
                                                    <th scope="row">GLUCOSA</th>
                                                    <td><?php echo $promedioGlucosa?></td>
                                                    <td>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentajeGlucosa?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <?php
                                                        if($promedioGlucosa>0 && $promedioGlucosa<=90){
                                                            echo '<td><span class="status-p bg-success">Normal</span></td>';
                                                        };
                                                        if($promedioGlucosa>90 && $promedioGlucosa<=126){
                                                            echo '<td><span class="status-p bg-warning">Preocupante</span></td>';
                                                        };
                                                        if($promedioGlucosa>126 && $promedioGlucosa<=200){
                                                            echo '<td><span class="status-p bg-warning">Muy preocupante</span></td>';
                                                        };
                                                    ?>
                                                </tr>

                                                <tr>
                                                    <th scope="row">ACTIVIDAD FISICA</th>
                                                    <td><?php echo $promedioActividadFisica?></td>
                                                    <td>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentajeActividadFisica?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <?php
                                                        if($promedioActividadFisica>0 && $promedioActividadFisica<=1.5){
                                                            echo '<td><span class="status-p bg-warning">Bajo</span></td>';
                                                        };
                                                        if($promedioActividadFisica>1.5 && $promedioActividadFisica<=3){
                                                            echo '<td><span class="status-p bg-primary">Normal</span></td>';
                                                        };
                                                        if($promedioActividadFisica>3 && $promedioActividadFisica<=7){
                                                            echo '<td><span class="status-p bg-success">Alto</span></td>';
                                                        };
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Striped table start -->
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Striped Rows</h4>
                                <div class="single-table">
                                    <div class="table-responsive">
                                        <table class="table table-striped text-center">
                                            <thead class="text-uppercase">
                                                <tr>
                                                    <th scope="col">DNI</th>
                                                    <th scope="col">NOMBRE</th>
                                                    <th scope="col">EDAD</th>
                                                    <th scope="col">ALIMENTACION</th>
                                                    <th scope="col">GENETICA</th>
                                                    <th scope="col">GLUCOSA</th>
                                                    <th scope="col">ACTIVIDAD FISICA</th>
                                                    <th scope="col">RESULTADO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // $sql = "select e.dni,e.nombre,e.edad, etv.id_variable, etv.resultado as resultado_variable, et.resultado as resultado_riesgo from estudiante_test_variable etv
                                                // inner join estudiante_test et on etv.id_test_estudiante = et.id
                                                // inner join estudiante e on et.id_estudiante = e.dni
                                                // where et.id_test=$id";
                                                $sql = "select  e.dni,e.nombre,e.edad,et.resultado from estudiante e
                                                inner join estudiante_test et on e.dni=et.id_estudiante
                                                where id_test=$id";
                                                $result = $cnx->query($sql) or die("error");
                                                while($reg=$result->fetchObject()){
                                                    $dni = $reg->dni;
                                                    echo "
                                                        <tr>
                                                            <th scope='row'>$dni</th>
                                                            <td>$reg->nombre</td>
                                                            <td>$reg->edad</td>
                                                    ";
                                                    //ALIMENTACION
                                                    $sql = "select etv.resultado
                                                    from estudiante_test_variable etv
                                                    inner join estudiante_test et on etv.id_test_estudiante=et.id
                                                    where etv.id_variable=1 and et.id_estudiante='$dni' and et.id_test=$id;";
                                                    $r = $cnx->query($sql) or die("error");
                                                    if($re=$r-> fetchObject()){
                                                       echo "<td>$re->resultado</td>";
                                                    }
                                                    //GENETICA
                                                    $sql = "select etv.resultado
                                                    from estudiante_test_variable etv
                                                    inner join estudiante_test et on etv.id_test_estudiante=et.id
                                                    where etv.id_variable=2 and et.id_estudiante='$dni' and et.id_test=$id;";
                                                    $r = $cnx->query($sql) or die("error");
                                                    if($re=$r->fetchObject()){
                                                       echo "<td>$re->resultado</td>";
                                                    }
                                                    //GLUCOSA
                                                    $sql = "select etv.resultado
                                                    from estudiante_test_variable etv
                                                    inner join estudiante_test et on etv.id_test_estudiante=et.id
                                                    where etv.id_variable=3 and et.id_estudiante='$dni' and et.id_test=$id;";
                                                    $r = $cnx->query($sql) or die("error");
                                                    if($re=$r->fetchObject()){
                                                       echo "<td>$re->resultado</td>";
                                                    }
                                                    //ACTIVIDAD FISICA
                                                    $sql = "select etv.resultado
                                                    from estudiante_test_variable etv
                                                    inner join estudiante_test et on etv.id_test_estudiante=et.id
                                                    where etv.id_variable=1 and et.id_estudiante='$dni' and et.id_test=$id;";
                                                    $r = $cnx->query($sql) or die("error");
                                                    if($re=$r->fetchObject()){
                                                       echo "<td>$re->resultado</td>";
                                                    }
                                                    echo "<td>$reg->resultado</td>";
                                                }
                                                ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Striped table end -->

                </div>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2018. All right reserved. Template by <a href="https://colorlib.com/wp/">Colorlib</a>.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->
    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
            <li><a data-toggle="tab" href="#settings">Settings</a></li>
        </ul>
        <div class="offset-content tab-content">
            <div id="activity" class="tab-pane fade in show active">
                <div class="recent-activity">
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-check"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Added</h4>
                            <span class="time"><i class="ti-time"></i>7 Minutes Ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You missed you Password!</h4>
                            <span class="time"><i class="ti-time"></i>09:20 Am</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Member waiting for you Attention</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You Added Kaji Patha few minutes ago</h4>
                            <span class="time"><i class="ti-time"></i>01 minutes ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Ratul Hamba sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Hello sir , where are you, i am egerly waiting for you.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                </div>
            </div>
            <div id="settings" class="tab-pane fade">
                <div class="offset-settings">
                    <h4>General Settings</h4>
                    <div class="settings-list">
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch1" />
                                    <label for="switch1">Toggle</label>
                                </div>
                            </div>
                            <p>Keep it 'On' When you want to get all the notification.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show recent activity</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch2" />
                                    <label for="switch2">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show your emails</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch3" />
                                    <label for="switch3">Toggle</label>
                                </div>
                            </div>
                            <p>Show email so that easily find you.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show Task statistics</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch4" />
                                    <label for="switch4">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch5" />
                                    <label for="switch5">Toggle</label>
                                </div>
                            </div>
                            <p>Use checkboxes when looking for yes or no answers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- offset area end -->
    <!-- ReportePIE -->
    <script>
        var ctx = document.getElementById("chart-area").getContext('2d');
        var chart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ["BAJO", "NORMAL","ALTO", "CRITICO"],
            datasets: [{
              backgroundColor: [
                window.chartColors.green,
                window.chartColors.yellow,
                window.chartColors.red,
                window.chartColors.purple

              ],
              data: [<?php
                echo $contadorBajo.','.$contadorNormal.','.$contadorAlto.','.$contadorCritico    
              ?>]
            }]
          },
          options: {
            legend: {
              display: false
            },
          }
        });

        var myLegendContainer = document.getElementById("legend");
        // generate HTML legend
        myLegendContainer.innerHTML = chart.generateLegend();
        // bind onClick event to all LI-tags of the legend
        var legendItems = myLegendContainer.getElementsByTagName('li');
        for (var i = 0; i < legendItems.length; i += 1) {
          legendItems[i].addEventListener("click", legendClickCallback, false);
        }

        function legendClickCallback(event) {
          event = event || window.event;

          var target = event.target || event.srcElement;
          while (target.nodeName !== 'LI') {
            target = target.parentElement;
          }
          var parent = target.parentElement;
          var chartId = parseInt(parent.classList[0].split("-")[0], 10);
          var chart = Chart.instances[chartId];
          var index = Array.prototype.slice.call(parent.children).indexOf(target);
          var meta = chart.getDatasetMeta(0);
          console.log(index);
          var item = meta.data[index];

          if (item.hidden === null || item.hidden === false) {
            item.hidden = true;
            target.classList.add('hidden');
          } else {
            target.classList.remove('hidden');
            item.hidden = null;
          }
          chart.update();
        }
    </script>
    
    <!-- jquery latest version -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>

    <!-- others plugins -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>

    <!-- Fuzzy -->
    <script src="./js/fuzzy-min.js"></script>

    <!-- Grafico conjuntos difusos -->
        <!-- start chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <!-- start highcharts js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <!-- start amcharts -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/ammap.js"></script>
    <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <!-- all line chart activation -->
    <script src="assets/js/line-chart.js"></script>
    <!-- all pie chart -->
    <script src="assets/js/pie-chart.js"></script>
    <!-- all bar chart -->
    <script src="assets/js/bar-chart.js"></script>
    <!-- all map chart -->
    <script src="assets/js/maps.js"></script>


</body>

</html>
