<?php 
require_once 'templeat/header.php';

if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alertas'] = 'Por favor introducir un usuario';
    echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}

if (isset($_POST['materia']) && isset($_POST['ano']) && isset($_POST['lapso'])) {
    $ano = $_POST['ano'];
    $id_pensum = $_POST['materia'];  
    $lapso = $_POST['lapso'];
 
   
   
    
}elseif(isset($_GET['materia']) && isset($_GET['ano']) && isset($_GET['lapso'])){
    $ano = $_GET['ano'];
    $id_pensum = $_GET['materia']; 
    $lapso = $_GET['lapso'];
    
}else{
    $_SESSION['alerta']['plan'] = 'los campos no deben estar vacios';
    echo '<script>window.location="notas.php"</script>';
}
 if (isset($id_pensum) && $ano) {
     $sql = "select p.id, p.id_ano, p.id_materia,p.id, m.materia, a.ano, s.seccion from pensum p inner join materia m on p.id_materia = m.id inner join ano a on p.id_ano = a.id inner join seccion s on a.id_seccion = s.id where p.id = $id_pensum and p.id_ano = $ano";
     $guardar = mysqli_query($db, $sql);
    $notas_total = array();

    $periodo = $_SESSION['periodos']['periodo'];
    
    //planificacion(cantidad de evaluaciones)
    $sql_plan = "select * from planificacion where id_pensum = $id_pensum and lapso = $lapso";
    $guardar_plan =mysqli_query($db, $sql_plan);
    $plan = mysqli_fetch_assoc($guardar_plan);

    if (mysqli_num_rows($guardar_plan) == 0) {
        $_SESSION['alerta']['plan'] = 'Por favor realizar la planificacion de esta materia ';
        echo '<script>';
        echo 'alert("Por favor realizar la planificacion de esta materia")
            window.location=" notas.php"';
         echo '</script>';
    }
}




?>
<main>

    
    <?php if(isset($_SESSION['guardado']['exito'])):?>
    <div class="alert alert-success" role="alert">
        <?php echo $_SESSION['guardado']['exito']?>
      </div>
<?php elseif(isset($_SESSION['guardado']['error'])): ?> 
    <div class="alert alert-danger" role="alert">
        <?php echo $_SESSION['guardado']['error'] ?>
      </div>
<?php endif; ?>

<div class="container mt-2" style="height: 500px;  position: relative;">
    <div class="ayuda">
    
        <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    
            
                
                                    <?php 
                                    if(!empty($guardar) && isset($guardar)) 
                                            $guardado = mysqli_fetch_assoc($guardar);
                                            if (!empty($guardado)): ?>
                                                                    
                                                                    <div> <h4>Registro de notas de estudiantes de: <?=$guardado['materia']?> / <?=$guardado['ano'].' / '.$guardado['seccion'] ?></h4></div>
                                        <?php endif; ?>                             
                </div>
                <div class="">
                    <div class="">
                        <table class="table align-middle ">
                            <thead>
                                <tr>
                                        
                                
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Notas</th> 
                                   
                                   
                                    <th scope="col" class="lapso">Lapso</th>
                                    <th scope="col">ENVIAR</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                        
                                <?php
                                        $periodo = $_SESSION['periodos']['id'];
                                        $periodo_name = $_SESSION['periodos']['periodo'];
                                    if (isset($ano)){
                                        $alumnos = ConseguirTodosEstudiantes($db, $ano, $periodo);   
                                        
                                    }else{
                                        echo 'error1'; 
                                    }
                                    if (!empty($alumnos)):
                                            while($alumno = mysqli_fetch_assoc($alumnos)):  
                                                
                                                
                                            ?>
                                                
                                                
                                                
                                                <tr class="aaa">
                                                    <form action="notas_form.php" method="POST" > 
                                       

                                        <td><?= $alumno['id_alumno']?></td>
                                        <td><?= $alumno['nombre']?></td>
                                        <td><?= $alumno['apellido'] ?></td>
                                        <td><?= $alumno['cedula'] ?></td>
                                        <td>   
                                        <?php
                                            if(existeNota($db, $alumno['id_alumno'], $guardado['id'], $lapso, $periodo_name) ){
                                                $notas_total = "select notas.id, notas.nota, notas.lapso, pensum.id_materia from notas inner join pensum on notas.id_pensum = pensum.id where notas.id_alumno = ".$alumno['id_alumno']." and id_pensum = ".$guardado['id']." and lapso =". $lapso. " and notas.periodo = '$periodo_name'";
                                                $notas = mysqli_query($db, $notas_total);
                                                //contando la cantidad de notas que tengo
                                                $notas_count = mysqli_num_rows($notas);
                                                $i = 0;
                                                
                                                while($nota = mysqli_fetch_assoc($notas)){
                                                    
                                                            $i++;
                                                    
                                                                echo '<input type="number" maxlength="5" min="0" max="20" name="nota'. $i .'" value="'.$nota['nota'].'">';
                                                                echo '<input type="hidden" value="'.$nota['id'].'" name="idnota'.$i.'">';
                                                                
    
                                                               
                                                    }

                                                 
                                                    
                                                    
                                                }else{
                                                    
                                                    for($i = 1; $i <= $plan['cantidad_evaluaciones']; $i++){
                                                        echo '<input type="number" maxlength="5" name="nota'.$i.'" min="0" max="20">';  
                                                    }
                                                    
                                                }
                                             ?>
                                                </td>
                                              
                                                <td><?=$lapso?></td>
                                                
                                                <input type="hidden" name="cantidad_notas" value="<?=$plan['cantidad_evaluaciones']?>">
                                                <input type="hidden" name="lapso" value="<?=$lapso?>">
                                                <input type="hidden" name="alumno" value="<?= $alumno['id_alumno']?>">
                                                <input type="hidden" name="pensum" value="<?=$guardado['id']?>">
                                                <input type="hidden" name = "materia" value="<?=$id_pensum?>">
                                                <input type="hidden" name="ano" value="<?=$ano?>">
                                                
                                                <td><input type="submit" value="Enviar Notas"></td>
                                            </form>
                                                
                                                <?php
                                            endwhile;
                                        endif;
                                        ?>     
                                    
                                        
                                </tr>
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
<?php
include_once 'templeat/footer.php'; 
borrarErrores();

?>