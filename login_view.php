<?php 
session_start();
require_once 'model/conexion.php';

if (!empty($_POST["cargo"]) && !empty($_POST["password"]) && !empty($_POST['nombre'])) {
    $alertas = array();
    //validar nombre
    if (isset($_POST["nombre"]) && is_string($_POST["nombre"]) && !preg_match("/[0-9]/", $_POST["nombre"])) {
        $nombre = trim($_POST["nombre"]);
        $nombre_limpio =trim($nombre);
        $nombre_escapado = mysqli_escape_string($db, $nombre_limpio);
    }else{
        $alertas['nombre'] = 'Por favor introducir un nombre valido';
        
    }
    
    //Validar usuario
    if (isset($_POST["cargo"]) && filter_var($_POST["cargo"], FILTER_VALIDATE_EMAIL)) {
        $cargo = trim($_POST["cargo"]);
        $cargo_escapado = mysqli_escape_string($db, $cargo);
    }else{
        $alertas['cargo'] = 'Por favor introducir un email valido';
        
    }
    //Validar COntraseña
    if (isset($_POST["password"]) && preg_match("/[a-zA-Z]/", $_POST["password"]) && preg_match("/[0-9]/", $_POST["password"])){
        $password = trim($_POST["password"]);
        $password_escapado = mysqli_escape_string($db, $password);
        //encriptado
        $encode = base64_encode($password_escapado);
        
    }else{
        $alertas['password'] = 'Por favor introducir una password valida';
        
    }
    if (count($alertas) == 0) {
        
        $sql = "select * from usuarios where nombre = '$nombre_escapado' and email = '$cargo_escapado' and password = '$encode'";
        $guardar = mysqli_query($db, $sql);
        if ($guardar == true && mysqli_num_rows($guardar) > 0) {
            $usuario = mysqli_fetch_assoc($guardar);
            $usuario_name = $usuario['nombre'];
            $usuario_id = $usuario['id'];
            
            $movimiento = "El usuario ". $usuario_name. " ha iniciado sesión";
            $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
           $query = mysqli_query($db, $sqli);
           if ($query) {
            
            if($usuario['id_rol'] == 1){
                $_SESSION['usuario_admin'] = $usuario; 
                header('location: index.php');
                
            }elseif($usuario['id_rol'] == 2){
                $_SESSION['usuario_lector'] = $usuario;
                header('location: index.php');
            }
        }
    }else{
        $_SESSION['alerta'] = 'Usuario desconocido';
        header('location: login_form.php');
        
    }
}else{
    $_SESSION['alertas'] = $alertas;
         header('location: login_form.php');
}
}elseif($_GET['gmail']){
    
    header('location: login_form.php');
}else {
    
    $_SESSION['alerta'] = 'Error al iniciar sesion';
    header('location: login_form.php');
}

?>