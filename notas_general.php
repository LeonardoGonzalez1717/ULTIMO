<?php 
require_once 'templeat//header.php';
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alertas'] = 'Por favor introducir un usuario';
    echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}
if (isset($_GET['alumno'])) {
    $periodo = $_SESSION['periodos']['periodo'];
    $id_alumno = $_GET['alumno'];
    $id_ano = $_GET['ano'];
    
    

    $sql = "select nombre, apellido from alumno where id = $id_alumno";
    $guardar = mysqli_query($db, $sql);
    
    $guardado = mysqli_fetch_assoc($guardar);
  

      
}else{
    echo '<script>';
    echo 'window.location="index.php"';
     echo '</script>';
     exit;
}
?>
<?php if (isset($_SESSION['alerta'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['alerta']?>
        </div>
      <?php endif; ?>
<div class="container container-notas mt-2">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola" style="position: relative;">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                <?php if(mysqli_num_rows($guardar) > 0): ?>
                    <p style="margin: 0;"> Listado de notas de <?=$guardado['nombre'].' '.$guardado['apellido']?> </p>
                <?php else: ?>
              
                    <p style="color: black; background-color: red; border-radius: 20px; font-weight: bold;" >No hay notas para mostrar</p>
                    <?php endif; ?>
                                    
                </div>
                <div class="">
                    <div class="">
                        <table class="table align-middle ">
                            <thead>
                                <tr>
                                    
                                    <th scope="col">Materia</th>
                                    <th scope="col">Promedio</th>
                                    <th scope="col">Notas detalladas</th>
                                   
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $promedio_general = array();
                               
                               
                                    $sql = "SELECT m.materia, p.id FROM pensum p INNER JOIN materia m on m.id = p.id_materia  WHERE p.id_ano = $id_ano and cursando = '$periodo'"; 
                                    $guardar = mysqli_query($db, $sql);
                                        if (mysqli_num_rows($guardar) > 0) {
                                            
                                        
                                        while($guardado = mysqli_fetch_assoc($guardar)){
                                            $promedios_total = array();
                                        $promedios_final = '';
                                          $id_pensum = $guardado['id'];
                                    
                                       
                                    ?>
                                    <tr class="">
                                        <td><?= $guardado['materia']?></td>
                                        
                                        <?php
                                            $sql = "SELECT AVG(nota) AS promedio FROM notas  where id_alumno = $id_alumno and id_pensum = $id_pensum and periodo = '$periodo' GROUP BY id_pensum, lapso;"; 
                                            $promedios = mysqli_query($db, $sql);
                                            if (mysqli_num_rows($promedios) > 0) {
                                                while($promedio = mysqli_fetch_assoc($promedios)){
                                                //colocar "[]" para especificar que es un array
                                                $promedios_total[] = $promedio['promedio'];
                                              
                                                $promedios_final = array_sum($promedios_total)/count($promedios_total);
                                                $promedios_final = number_format($promedios_final, 2);
                                                
                                            } 
                                            $promedio_general[] = $promedios_final;
                                            $promedio_global = array_sum($promedio_general)/count($promedio_general);
                                        }
                                         if (!empty($promedios_final)): ?>
                                            <td><?=$promedios_final?></td>  
                                            <?php else: ?>
                                                <td>No hay notas registradas</td>  
                                                <?php endif; ?>
                                                <td><a title="Notas detalladas" class="text-success" href="notas_estudiantes.php?alumno=<?=$id_alumno?>&pensum=<?=$guardado['id'] ?>"><i class="bi bi-archive-fill"></i></a></td>
                                                
                                                
                                                
                                            </tr>
                                            
                                            <?php } 
                                        }else {
                                            echo '<tr>';
                                            echo '<td> No hay pensum creado</td>';
                                            echo '</tr>';
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
                <?php
                            if (!empty($promedio_global)) {
                                    $promedio_global = number_format($promedio_global, 2);
                                     echo '<p style="padding: 10px; font-weight: bold;"> Promedio final: '.$promedio_global.'</p>'; 
                            }
                            ?>

                              <div class="btn-crear">

                                      <?php $sql = "SELECT DISTINCT lapso FROM `notas` where id_alumno = $id_alumno and periodo = '$periodo' order by lapso";
                                         $boletines = mysqli_query($db, $sql);
                                         $boletin_count = mysqli_num_rows($boletines);
                                         
                                         
                                         while($boletin = mysqli_fetch_assoc($boletines)):
                                             ?>
                                                     <a href="boletin.php?lapso=<?=$boletin['lapso']?>&alumno=<?=$id_alumno?>&ano=<?=$id_ano?>"> Crear boletin lapso <?=$boletin['lapso']?></a>
                                                     
                                                     <?php 
                                             
                                         endwhile;
                                         if ($boletin_count == 3):?>
                                             <a href="boletin.php?alumno=<?=$id_alumno?>&ano=<?=$id_ano?>">Crear boletin</a>
                                             <?php endif; 
                                             ?>
                                </div>
</div>


<?php 
borrarErrores();
require_once 'templeat/footer.php'; 
?>