<?php 
include_once 'templeat/header.php';
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alerta'] = 'Por favor introducir un usuario';
    echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}
?>
<div class="container mt-2" style="height: 500px;  position: relative;">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card hola">
                <div class="card-header">
                    
                    
                 Lista de estudiantes sin año asignado
                 <?php 
                  if (isset($_SESSION['alertas']['periodo'])) {
                    echo '<span style"color: green>'.$_SESSION['alertas']['periodo'].'</span>';
                    
                    }elseif (isset($_SESSION['Usuario']['exito'])) {
                    
                        echo '<span style"color: green;">'.$_SESSION['Usuario']['exito'].'</span>';
                    }
                    ?>
            
           
                </div>
                <div class="p-4">
                    <div class="">
                        <table class="table align-middle ">
                            <thead style="position: inherit;">
                                <tr>
                                
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Edad</th>
                                 
                                    <th scope="col">Secciones</th>
                                    <th scope="col">Periodo</th>
                                    <th scope="col">Enviar</th>
                                    
                                </tr>
                                    
                            </thead>
                            <tbody>
                            <?php $alumnos = noconseguirEstudiantes($db); 
                                if (!empty($alumnos)):
                                    while ($alumno = mysqli_fetch_assoc($alumnos)):

                                       
                        ?>
                            <form action="administrar.php" method="POST">
                                <tr class="">
                                    <td scope="row"><?= $alumno['id'] ?></td>
                                    <td><?= $alumno['nombre']?></td>
                                    <td><?= $alumno['apellido'] ?></td>
                                    <td><?= $alumno['cedula'] ?></td>
                                    <td><?= $alumno['edad'] ?></td> 
                                    
                                    <td>
                                        <select name="ano" class="select">
                                        <?php $sql = "select ano.*, s.seccion from ano inner join seccion s on ano.id_seccion = s.id order by ano.id";
                                                $guardar = mysqli_query($db, $sql);
                                                while($ano = mysqli_fetch_assoc($guardar)){
                                                    ?>
                                            <option value="<?=$ano['id']?>"><?=$ano['ano']. ' ' .$ano['seccion'] ?> </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                    <?=$_SESSION['periodos']['periodo']  ?>
                                    </td>
                                    <input type="hidden" name="estudiante" value="<?=$alumno['id']?>" >
                                    <td><input type="submit"></td>
                                </tr>
                            </form>
                                <?php
                                endwhile;
                             endif; 
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
include_once 'templeat/footer.php'; 
borrarErrores();

?>