<?php 
session_start();
require 'model/conexion.php';
//borrar estudiantes
if (isset($_GET['id_alumno']) && $_GET['ano']) {

$codigo = $_GET['id_alumno'];
$ano = $_GET['ano'];
$periodo = $_SESSION['periodos']['id'];

$url = "estudiantes.php";
$url .= "?id=" .urldecode($ano);
$query_alumno = "select * from alumno where id = $codigo";
$query_a = mysqli_query($db, $query_alumno);
$query_as = mysqli_fetch_assoc($query_a);
$alumno_name = $query_as['ginombre'];

$sql = ("DELETE c, a  FROM cursando c inner join alumno a on c.id_alumno = a.id where a.id = $codigo and c.id_periodo = $periodo");
$resultado = mysqli_query($db, $sql);

if (isset($_SESSION['usuario_admin'])) {
    $usuario_name = $_SESSION['usuario_admin']['nombre'];
    $usuario_id = $_SESSION['usuario_admin']['id'];
}else{
    $usuario_name = $_SESSION['usuario_lector']['nombre'];
    $usuario_id = $_SESSION['usuario_lector']['id'];
}
    $movimiento = "El usuario " . $usuario_name . " ha eliminado al Alumno ". $alumno_name. " con exito";
    $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
    $query = mysqli_query($db, $sqli);
if ($query) {
    
    if ($resultado === TRUE) {
        $_SESSION['eliminado']['exito'] = 'Estudiante eliminado con exito';
        header('location: '. $url);
    } else {
        $_SESSION['eliminado']['error'] = 'error al eliminar el estudiante' ;
        header('location: '.$url);
    }
}
//borrar usuario
}elseif(isset($_GET['usuario'])) {

   
    $usuario = $_GET['usuario'];

    if (isset($_SESSION['usuario_admin'] )) {
        $nombre = $_SESSION['usuario_admin']['nombre']; 
        $cargo = $_SESSION['usuario_admin']['cargo']; 
        $sql = "select * from usuarios where nombre = '$nombre' and cargo = '$cargo' and id = '$usuario'";
        $guardar = mysqli_query($db, $sql);
        
        $sqli = "select nombre from usuarios where id = $usuario";
        $query_u = mysqli_query($db, $sqli);
        $query_us = mysqli_fetch_assoc($query_u);

        $usuario_name = $query_us['nombre'];
        $usuario_id = $_SESSION['usuario_admin']['id'];
        
            
            
            if ($guardar == true && mysqli_num_rows($guardar) == 1) {
                
                $_SESSION['alerta']['usuario'] = 'No puede borrar el usuario en uso!';
                header('location: admin.php');
                
            }else{
                $movimiento = "El usuario " . $nombre . " ha eliminado al usuario ". $usuario_name;
                $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                $query = mysqli_query($db, $sqli);
                if ($query) {
                $sql = "delete from usuarios where id = $usuario";
                $guardar = mysqli_query($db, $sql);
                if ($guardar) {
                    $_SESSION['guardado'] = 'Usuario eliminado con exito';
                    header('location: admin.php');
                }else {
                    echo 'error';
                }
            }
        }

    }
    
}
?>
