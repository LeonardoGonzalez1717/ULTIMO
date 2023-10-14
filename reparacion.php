<?php require_once 'templeat/header.php';
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alertas'] = 'Por favor introducir un usuario';
    echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}

$periodo = $_SESSION['periodos']['periodo'];
$sql = "select distinct an.nombre, a.ano, r.cantidad_evaluacion, r.id_alumno, r.periodo from reparacion r inner join alumno an on an.id = r.id_alumno inner join ano a on a.id = r.id_ano where r.periodo = '$periodo'";
$guardar = mysqli_query($db, $sql);
?>

<style>
    form{
        display: flex;
        flex-direction: column;
        gap: 30px;
        border: .5px solid var(--blue);
        height: 250px;
        /* padding: 50px 60px; */
        justify-content: center;
        padding: 0px 30px;
        border-radius: 7px;
    }
</style>

<main>
<?php
$id_ano = $_GET['id_ano'];
$sqli = "select id from reparacion where cantidad_evaluacion is not null and id_ano = $id_ano and periodo = '$periodo'";
$query = mysqli_query($db, $sqli);
if (mysqli_num_rows($query) == 0 ) {
    echo '<form action="plan_reparacion.php" method ="post">
    <label> Cantidad de evaluaciones
    </label>
    <input type="number" name ="cantidad" required>    
    <input type="hidden" name= "id_ano" value="'.$id_ano.'">
    <input type="submit">
    </form>';
}else{

    ?>    
<div class="container mt-2" style="height: 500px;  position: relative;">
<div class="ayuda">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header" style="position: sticky; top: 0; background-color: white;">
                    
                <H2>Estudiantes en reparación</H2>
           
                </div>
                <div class="">
                    <div class="">
                        <table class="table align-middle ">
                            <thead>
                                <tr>
                                        
                                <?php   
                                if (mysqli_num_rows($guardar) > 0) { ?>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Año</th>
                                    <th scope="col">Periodo</th>
                                    <th scope="col">Notas</th>
                                   <?php }else{
                                       echo '<th>Advertencia</th>';
                                    } ?>
                                    
                                </tr>
                            </thead>
                            <tbody>
                        
                                <?php   
                                if (mysqli_num_rows($guardar) > 0) {
                                    
                                    while($alumno = mysqli_fetch_assoc($guardar)):    
                                        ?>
                                <tr class="">
                                    
                                    <td><?= $alumno['nombre']?></td>
                                    <td><?= $alumno['ano'] ?></td>
                                    <td><?= $alumno['periodo'] ?></td>
                                    <td><a href="reparacion_view.php?alumno=<?=$alumno['id_alumno']?>" >Detalles</a></td>
                                    
                                    
                                </tr>
                                
                                <?php
                            endwhile;
                        }else{
                            echo '<td>No hay estudiantes en reparación</td>';
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


<?php
        }
echo '</main>';
 require_once 'templeat/footer.php' ?>