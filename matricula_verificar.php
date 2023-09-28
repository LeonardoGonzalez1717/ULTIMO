<?php 
require_once 'templeat/header.php';

if (isset($_GET['alumno'])) {
    $periodo_id = 0;
    $id_alumno = $_GET['alumno'];
    $ano = $_GET['ano'];
    $periodo = $_SESSION['periodos']['periodo'];
    $periodo_id = $_SESSION['periodos']['id'];
    $ano_nuevo = $ano + 3;
    $periodo_id_new = $periodo_id + 1;
    $periodo_query = "select periodo from periodo where id = $periodo_id_new";
    $row = mysqli_query($db, $periodo_query);
    $rows = mysqli_fetch_assoc($row);

    $new_ano = "select a.ano, s.seccion from ano a inner join seccion s on s.id = a.id_seccion where a.id = $ano_nuevo";
    $row_ano = mysqli_query($db, $new_ano);
    $rows_ano = mysqli_fetch_assoc($row_ano);

    $lapso_count = "select DISTINCT lapso from notas where id_alumno = '$id_alumno' and periodo = '$periodo' order by lapso";
    $lapsos_query_count = mysqli_query($db, $lapso_count);
     $prueba = mysqli_num_rows($lapsos_query_count);
    
     $row_a = "select nombre, apellido from alumno where id = $id_alumno";
     $query_a = mysqli_query($db, $row_a);
     $querys_a = mysqli_fetch_assoc($query_a); 
    
      
     $sql_periodo = "select * from periodo where id = $periodo_id_new";
     $query_periodo = mysqli_query($db, $sql_periodo);
     if (mysqli_num_rows($query_periodo) == 0) {
         $_SESSION['alerta'] = 'Error! periodo no creadooooo';
         echo '<script>';
          echo "var ano ='". $ano . "';";
          echo 'window.location=" matricula_view.php?ano="+ ano;'  ;
          echo '</script>';
        }
        
    }
    ?>
<main>
    
<div class="container mt-2" style="height: 500px;  position: relative;">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    <h2 style="display: flex;">Matriculación de: <span style="color: green;"><?=$querys_a['nombre'].' '.$querys_a['apellido']?></span></h2>
                </div>
                <div class="">
                    <div class="">
                    <table class="table align-middle ">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Lapso 1</th>
                                <th>Lapso 2</th>
                                <th>Lapso 3</th>
                                <th>Promedio</th>
                                <th>Aprobado o reprobado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php           
                        if (mysqli_num_rows($lapsos_query_count) == 3) {
                        $periodo = $_SESSION['periodos']['periodo'];
                        $materia_count = array();
                        $sqli = "SELECT DISTINCT m.materia, n.id_pensum FROM notas n inner join pensum p on n.id_pensum = p.id inner join materia m on m.id = p.id_materia WHERE id_alumno = $id_alumno and periodo = '$periodo' order by n.id_pensum, n.lapso";
                        $guardar = mysqli_query($db, $sqli); 
                        $count_evaluacion = mysqli_num_rows($guardar);
                        $i = 0;
                        while($materia = mysqli_fetch_assoc($guardar)){
                            $i++;
                            $materia_al = $materia['materia'];
                            $pensum = $materia['id_pensum'];
                            ?>

                            <tr class="">
                                <td><?= $materia_al ?></td>
                                <?php
                                $sql = "select DISTINCT lapso from notas where id_alumno = $id_alumno and id_pensum = $pensum and periodo = '$periodo' order by lapso";
                                $lapsos_query = mysqli_query($db, $sql);
                                    
                                    while ($lapsos = mysqli_fetch_assoc($lapsos_query)) {
                                        
                                        $lapso = $lapsos['lapso'];
                                        $sql = "select nota from notas where id_alumno = $id_alumno and id_pensum = $pensum and lapso = $lapso and periodo = '$periodo' order by lapso";
                                        $notas = mysqli_query($db, $sql);
                                    echo '<td style="border-right: 1px solid black; border-left: 1px solid black;">';
                                    if (mysqli_num_rows($notas) > 0) {
                                        $notas_count = mysqli_num_rows($notas);
                                        
                                        while ($guardado = mysqli_fetch_assoc($notas)) {
                                           
                                            echo '<span style="margin-right: 5px;">' . $guardado['nota'] . '</span>';
                                        }
                                    }else {
                                        echo 'No hay notas registradas';
                                    }
                                }
                                echo '</td>';
                                $promedio = ConseguirNotas($db, $id_alumno, $pensum, $periodo);
                        $promedio_materia = array();
                        while($promedios = mysqli_fetch_assoc($promedio)){
                            $promedio_materia[] = $promedios['promedio'];
                            $promedios_final = array_sum($promedio_materia)/count($promedio_materia);
                            $promedios_final = number_format($promedios_final, 2);
                        }
                        echo '<td>'.$promedios_final.'</td>';
                        if ($promedios_final >= 10) {
                            echo '<td style = "color: black; background-color: #b3d4ba;"> Aprobó </td>';
                            $materia_count[] = $promedios_final;
                        }else{
                            echo '<td style = "color: black; background-color: #f8d7da;"> Reprobó </td>';
                            echo '<div>El estudiante no aprobo La materia de: '.$materia_al. ', ira a reparación</div>';
                            $alumno_verificar = "select id_alumno from cursando where id_alumno = $id_alumno and id_periodo = $periodo_id_new";
                            $query = mysqli_query($db, $alumno_verificar);
                            if (mysqli_num_rows($query) > 0) {
                                $alumno_verificado = true;
                            }else{ 

                                if (isset($_SESSION['usuario_admin'])) {
                                    $usuario_name = $_SESSION['usuario_admin']['nombre'];
                                    $usuario_id = $_SESSION['usuario_admin']['id'];
                                }
                                
                                $reparacion_verify = "select * from reparacion where id_alumno = $id_alumno and periodo = '$periodo'";
                                $reparacion_query = mysqli_query($db,$reparacion_verify);
                                if (mysqli_num_rows($reparacion_query) > 0) {
                                    $reparacion_ya = true;
                                }else {
                                    
                                    
                                    
                                    
                                    $movimiento = "El usuario " . $usuario_name . " ha ingresado al alumno " . $querys_a['nombre'] . " a reparación";
                                    $sqli_auditoria = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                                    $query_auditoria = mysqli_query($db, $sqli_auditoria);
                                    if ($query_auditoria) {
                                        
                                        $insert_reparacion = "insert into reparacion values(null, $id_alumno, $pensum, null, '$periodo')";
                                        $reparacion = mysqli_query($db, $insert_reparacion);
                                    }
                                }
                            }
                             
                            
                        }
                        echo '</tr>';
                       
                    }
                  
                             
                    $count_mate = count($materia_count);
                    
                        if ($count_mate == $count_evaluacion) {
                        $alumno_verificar = "select id_alumno from cursando where id_alumno = $id_alumno and id_periodo = $periodo_id_new";
                        $query = mysqli_query($db, $alumno_verificar);
                    if (mysqli_num_rows($query) > 0) {
                        $alumno_verificado = true;
                      echo 'NOTA: el estudiante ya esta cursando el siguiente periodo';
                    }else{
                            $insert = "insert into cursando values(null, '$id_alumno', '$ano_nuevo', '$periodo_id_new')";
                            $guardarD = mysqli_query($db, $insert);
                                      
                            if (isset($_SESSION['usuario_admin'])) {
                                $usuario_name = $_SESSION['usuario_admin']['nombre'];
                                $usuario_id = $_SESSION['usuario_admin']['id'];
                            }else{
                                $usuario_name = $_SESSION['usuario_lector']['nombre'];
                                $usuario_id = $_SESSION['usuario_lector']['id'];
                            }
                    
                            $movimiento = "El usuario " . $usuario_name . " ha ingresado al alumno". $querys_a['nombre']." al proximo periodo";
                            $sqli_auditoria_1 = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                            $query_auditoria_1 = mysqli_query($db, $sqli_auditoria_1);
                            if ($query_auditoria_1) {
                              
                                if ($guardarD == true) {
                                    echo '<div>El estudiante ha logrado culminar todas sus actividades con exito, procede a cursar el nuevo periodo de '. $rows['periodo'].' el año '.$rows_ano['ano'].' '.$rows_ano['seccion'].'</div>';
                                    
                                }
                            }
                       
                    } 
                    
                            }
                }else{
                    echo 'Por favor completar de registrar las notas en todos los lapsos';
                }
               
                        ?>
                        </tbody>
                    </table>
                    </div>
            
                 </div>
             </div>
        
            </div>
        </div>
    </div>  
</div>
</main>
<?php require_once 'templeat/footer.php'; ?>