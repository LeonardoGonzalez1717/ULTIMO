<?php require_once 'templeat/header.php';
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alertas'] = 'Por favor introducir un usuario';
    echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}

if (isset($_GET['alumno'])) {
    $id_alumno = $_GET['alumno'];   
    
    $sql = "select a.nombre, r.id_alumno, r.id_ano from reparacion r inner join alumno a on a.id = r.id_alumno where r.id_alumno = $id_alumno";
    $query = mysqli_query($db, $sql);
    $querys = mysqli_fetch_assoc( $query);
    $id_alumno = $querys['id_alumno'];
    $id_ano = $querys['id_ano'];
    $periodo = $_SESSION['periodos']['periodo'];
    $sqli = "select cantidad_evaluacion from reparacion where id_ano= $id_ano";
    $queryi = mysqli_query($db, $sqli);
    $queryis = mysqli_fetch_assoc($queryi);
}else {
    echo '<script>';
    echo 'window.location="index.php"';
     echo '</script>';
     exit;
}
?>

<main>
<?php if(isset($_SESSION['guardado'])): ?>
          <div class="alert alert-warning" role="alert">
        <?php echo $_SESSION['guardado'] ?>
      </div>
      <?php endif;?>
<div class="container mt-2" style="height: 500px;  position: relative;">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    
                <H2>Estudiante <?=$querys['nombre'] ?> en reparación</H2>
           
                </div>
                <div class="">
                    <div class="">
                        <table class="table align-middle ">
                            <thead>
                                <tr>
                                    <th scope="col">Materias</th>
                                    <th scope="col">Evaluación</th>
                                    <th scope="col">Periodo</th>
                                    <th scope="col">Registrar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sqli = "select r.periodo,r.id_materia, r.id_ano, m.materia, r.id_ano, r.id from reparacion r inner join ano a on a.id = r.id_ano inner join alumno an on an.id = r.id_alumno inner join materia m on m.id = r.id_materia where id_alumno = $id_alumno";  
                                    $guardar = mysqli_query($db, $sqli); 
                                    $i = 0;
                                        while($alumno = mysqli_fetch_assoc($guardar)):  
                                            $id_pensum = $alumno['id_materia'];
                                            $id_reparacion = $alumno['id'];
                                            $periodo = $alumno['periodo'];
                                            $i++;  
                                            $ano = $alumno['id_ano'];
                                            ?>
                                            <form action="reparacion_form.php" method="POST">
                                        <tr class="">

                                        <td><?= $alumno['materia'] ?></td>
                                        <td class="flex">
                                        <?php if(existeNotaReparacion($db, $id_reparacion)){ 
                                            $sql = "select notas from reparacion_notas where id_reparacion = $id_reparacion";
                                            $query = mysqli_query($db, $sql);
                                            while($querys = mysqli_fetch_assoc($query)){

                                                
                                                echo '<span>' . $querys['notas'].'</span>'; 
                                            }
                                        }else{ 
                                            
                                            
                                            
                                            
                                            for ($o=1; $o<=$queryis['cantidad_evaluacion']; $o++) { 
                                                
                                                echo '<input type="number" maxlength="5" name="nota'.$o.'" min="0" max="20">';  
                                            } 
                                                  } ?>
                                                </td>
                                                <td><?= $alumno['periodo'] ?></td>
                                                <input type="hidden" name="id_materia" value="<?=$alumno['id_materia']?>">
                                                <input type="hidden" name="id_reparacion" value="<?=$alumno['id']?>">
                                                <td><input type="submit"></td>
                                                <input type="hidden" name="cuenta" value="<?=$queryis['cantidad_evaluacion']?>">
                                                <input type="hidden" name="alumno" value="<?=$id_alumno?>">
                                                <input type="hidden" name="ano" value="<?=$ano?>">
                                            </form>
                                                
                                                <?php endwhile; ?>     
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
borrarErrores();
require_once 'templeat/footer.php' ?>