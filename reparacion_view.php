<?php require_once 'templeat/header.php';
$sql = "select a.nombre, r.id_alumno from reparacion r inner join alumno a on a.id = r.id_alumno";
$query = mysqli_query($db, $sql);
$querys = mysqli_fetch_assoc( $query);
$id_alumno = $querys['id_alumno'];
$periodo = $_SESSION['periodos']['periodo'];
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
                                </tr>
                            </thead>
                            <tbody>
                                <form action="reparacion_form.php" method="POST">
                                <?php
                                
                                    
                                    $sqli = "select r.periodo,r.id_pensum, a.id, m.materia from reparacion r inner join pensum p on p.id = r.id_pensum inner join materia m on m.id = p.id_materia inner join ano a on a.id = p.id_ano where id_alumno = $id_alumno";  
                                    $guardar = mysqli_query($db, $sqli); 
                                    $count_alumnos = mysqli_num_rows($guardar);
                                    $i = 0;
                                        while($alumno = mysqli_fetch_assoc($guardar)):  
                                            $id_pensum = $alumno['id_pensum'];
                                            $i++;  
                                            $ano = $alumno['id'];
                                ?>
                                <tr class="">
                               
                               <td><?= $alumno['materia'] ?></td>
                                        <td><input type="number" name="nota<?=$i?>" required></td>
                                
                                 
                                        <td><?= $alumno['periodo'] ?></td>
                                    </tr>
                                    <?php
                                    endwhile;
                                    ?>     
                        </tbody>
                        <input type="hidden" name="cuenta" value="<?=$count_alumnos?>">
                        <input type="hidden" name="alumno" value="<?=$id_alumno?>">
                        <input type="hidden" name="ano" value="<?=$ano?>">
                        <input type="hidden" name="materia<?=$i?>" value="<?=$alumno['id_pensum']?>">
                        </table>
                        <input type="submit">
                        </form>
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