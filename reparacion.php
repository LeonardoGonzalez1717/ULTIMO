<?php require_once 'templeat/header.php';
$periodo = $_SESSION['periodos']['periodo'];
$sql = "select distinct an.nombre, a.ano, r.periodo, r.id_alumno from reparacion r inner join alumno an on an.id = r.id_alumno inner join pensum p on p.id = r.id_pensum inner join ano a on a.id = p.id_ano where cursando = '$periodo'";
$guardar = mysqli_query($db, $sql);
?>


<main>
    
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
                                        
                                
                                   
                                    <th scope="col">Nombre</th>
                                   
                                    <th scope="col">Año</th>
                                    <th scope="col">Periodo</th>
                                    <th scope="col">Notas</th>
                                   
                                    
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
                            echo 'No hay estudiantes en reparación';
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


<?php require_once 'templeat/footer.php' ?>