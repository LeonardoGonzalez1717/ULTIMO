<?php  
require_once 'model/conexion.php';
require_once 'helpers/funciones.php';
session_start();

$id_pensum = $_POST['materia'];
$ano = $_POST['ano'];

//convirtiendo string en enteros
$cantidad = (int)$_POST['cantidad_notas'];
$lapso = $_POST['lapso'];


$url = "notas_view.php";
$url .= "?materia=" .urlencode($id_pensum);
$url .= "&ano=" .urlencode($ano);
$url .= "&lapso=" .urlencode($lapso);
$url .= "&filas=" .urlencode($cantidad);

   
    if (isset($_POST['pensum']) && !empty($_POST['pensum']) ) {
        $pensum = $_POST['pensum'];
    }else{
        echo '<script>';
        echo 'window.location="index.php"';
         echo '</script>';
         exit;
    }
    
    if (isset($_POST['alumno']) && !empty($_POST['alumno'])) {
        $alumno =$_POST['alumno']; 
    }else{
        echo '<script>';
        echo 'window.location="index.php"';
         echo '</script>';
         exit;;
    }
                $periodo = $_SESSION['periodos']['periodo'];
                if(existeNota($db, $alumno, $pensum, $lapso, $periodo)) {
                    
       
                for($i = 1; $i <= $cantidad; $i++){

                  $idnota_total = $_POST['idnota'.$i];
                  $nota_total = $_POST['nota'.$i];

                  $sql = "update notas set id_alumno = '$alumno',id_pensum = '$pensum',nota = '$nota_total', lapso = '$lapso' where id = $idnota_total;";
                  $guardar = mysqli_query($db, $sql);
                  }
                  if ($guardar == true) {
                    $_SESSION['guardado']['exito'] = 'Nota Editada';
                    
                }else{
                    $_SESSION['guardado']['error'] = 'fallo al editar la nota';
                    header("Location: " . $url);
                    exit();
                }
                if (isset($_SESSION['usuario_admin'])) {
                    $usuario_name = $_SESSION['usuario_admin']['nombre'];
                    $usuario_id = $_SESSION['usuario_admin']['id'];
                }else{
                    $usuario_name = $_SESSION['usuario_lector']['nombre'];
                    $usuario_id = $_SESSION['usuario_lector']['id'];
                }
                    $movimiento = "El usuario " . $usuario_name . " ha editado una nota";
                    $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                    $query = mysqli_query($db, $sqli);
                    if ($query) {
                        header("Location: " . $url);
                        exit();
                    }
               
            }else{
                for ($i=1; $i<=$cantidad; $i++) { 
                   $nota_total = $_POST['nota'.$i]; 
                    
                    $sql = "insert into notas values(null, '$alumno', '$pensum', '$nota_total', '$lapso', '$periodo')";
                    $guardar = mysqli_query($db, $sql);
                }
                if ($guardar == true) {
                    $_SESSION['guardado']['exito'] = 'Nota registrada';
                    
                   
                }else{
                    $_SESSION['guardado']['error'] = 'Fallo al registrar nota';
                    header("Location: " . $url);
                    exit();
                }
                if (isset($_SESSION['usuario_admin'])) {
                    $usuario_name = $_SESSION['usuario_admin']['nombre'];
                    $usuario_id = $_SESSION['usuario_admin']['id'];
                }else{
                    $usuario_name = $_SESSION['usuario_lector']['nombre'];
                    $usuario_id = $_SESSION['usuario_lector']['id'];
                }
                    $movimiento = "El usuario " . $usuario_name . " ha registrado una nota";
                    $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                    $query = mysqli_query($db, $sqli);
                    if ($query) {
                        header("Location: " . $url);
                        exit();
                    }else{
                        echo 'error';
                    }

        }
        
    

    

?>


