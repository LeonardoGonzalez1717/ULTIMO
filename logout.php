<?php
require_once 'model/conexion.php';
session_start();

if (isset($_SESSION['usuario_admin'])) {
    $usuario_name = $_SESSION['usuario_admin']['nombre'];
    $usuario_id = $_SESSION['usuario_admin']['id'];
}else{
    $usuario_name = $_SESSION['usuario_lector']['nombre'];
    $usuario_id = $_SESSION['usuario_lector']['id'];
}

$movimiento = "El usuario " . $usuario_name . " ha cerrado sesión";
$sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
$query = mysqli_query($db, $sqli);

if ($_SESSION['usuario_admin']) {
    unset($_SESSION['usuario_admin']);
  

}elseif ($_SESSION['usuario_lector']) {
    unset($_SESSION['usuario_lector']);
  
}

header('location: login_form.php');