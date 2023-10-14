<?php 
//Conexion a la base de datos
require_once 'model/conexion.php';
session_start();
$alertas = array();

//Validacion de datos, registrando los datos q se envien del formulario atraves del $_POST, con el "Name" del formulario.
    if(!empty($_POST["nombre"]) || !empty($_POST["apellido"]) || !empty($_POST["cedula"]) || !empty($_POST["edad"])) {  

//Validacion de formulario
//nombre 
if (isset($_POST["nombre"]) && !is_numeric($_POST["nombre"]) && !preg_match("/[0-9]/", $_POST["nombre"])) {
    $nombre=$_POST["nombre"];
    $nombre_escapado = mysqli_real_escape_string($db, $nombre);
}else{
 
    $alertas['nombre'] = 'Nombre Erroneo, Verificarlo';
  
}
//apellido
if (isset($_POST["apellido"]) && !is_numeric($_POST["apellido"]) && !preg_match("/[0-9]/", $_POST["apellido"])) {
    $apellido=$_POST["apellido"];
    $apellido_escapado =  mysqli_real_escape_string($db, $apellido);
}else{
 
    $alertas['apellido'] = 'Apellido Erroneo, Verificarlo';
  
}
//cedula
if (isset($_POST["cedula"]) && is_numeric($_POST["cedula"]) && preg_match("/[0-9]/", $_POST["cedula"])) {
    $cedula= $_POST["cedula"];
    $cedula_escapado =  mysqli_real_escape_string($db, $cedula);
}else{
    $alertas['cedula'] = 'Cedula Erronea, Verificarla';
   
}

if (isset($_POST['edad']) || is_numeric($_POST['edad']) || preg_match("/[0-9]/", $_POST["edad"])) {
    $edad = $_POST['edad'];
    $edad_escapado = mysqli_real_escape_string($db, $edad);
}else {
    $alertas['edad'] = 'Verificar la edad';
   
}

if (count($alertas) == 0) {
    //vinculando los datos que nos lleguen del formulario a la base de datos
    $sql = "insert into alumno values(null, '$nombre_escapado', '$apellido_escapado', '$cedula_escapado', '$edad_escapado')";
    $guardar = mysqli_query($db, $sql);

    if (isset($_SESSION['usuario_admin'])) {
        $usuario_name = $_SESSION['usuario_admin']['nombre'];
        $usuario_id = $_SESSION['usuario_admin']['id'];
    }else{
        $usuario_name = $_SESSION['usuario_lector']['nombre'];
        $usuario_id = $_SESSION['usuario_lector']['id'];
    }
    
    $movimiento = "El usuario" .$usuario_name. " ha registrado un alumno";
    $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
   $query = mysqli_query($db, $sqli);
    
if ($query) {
    if ($guardar == true) {
        $_SESSION['alerta'] = 'Alumno registrado con exito';
        header('location: registrar_form.php');
        exit();
        
    }else {
        $_SESSION['alerta'] = 'Error al registrar Alumno';
        header('location: registrar_form.php');
        exit();
    }
}
 
    
}else {
    $_SESSION['alertas'] = $alertas;
    header('location: registrar_form.php');
}
}elseif(!empty($_POST['rol']) && !empty($_POST['nombre_usuario']) && !empty($_POST['cargo']) && !empty($_POST['password'])){
    //usuarios_nuevo
    $alertas = array();
    if(isset($_POST['nombre_usuario']) && is_string($_POST['nombre_usuario']) && !preg_match("/[0-9]/", $_POST['nombre_usuario'])){
        $nombre  = $_POST['nombre_usuario'];
        $nombre_escapado = mysqli_escape_string($db, $nombre);
    }else{
        $alertas['nombre'] = 'Nombre invalido';
    }

    if(isset($_POST['cargo'])  && is_string($_POST['cargo']) && !preg_match("/[0-9]/", $_POST['cargo'])){
        $cargo  = $_POST['cargo'];
    }else{
        $alertas['cargo'] = 'cargo invalido';
    }

    if(isset($_POST['rol'])){
        $rol_id = $_POST['rol'];
    }else{
        $alertas['rol'] = 'rol invalido';
    }


    if(isset($_POST['password'])){
        $password = $_POST['password'];
        if (preg_match("/[a-zA-Z]/", $password) && preg_match("/[0-9]/", $password)) {
            $password_escapado = mysqli_escape_string($db, $password);
            $hashpassword = base64_encode($password_escapado);
        }else{
            $alertas['password'] = 'contraseña invalido, debe tener numeros y letras';
        }
    }else{
        $alertas['password'] = 'la contraseña no puede estar vacia';
    }

    if (count($alertas) == 0) {

        $sql = "insert into usuarios values(null, '$nombre_escapado', '$cargo', '$hashpassword', '$rol_id')";
        $guardar = mysqli_query($db, $sql);

        if (isset($_SESSION['usuario_admin'])) {
            $usuario_name = $_SESSION['usuario_admin']['nombre'];
            $usuario_id = $_SESSION['usuario_admin']['id'];
        }

        $movimiento = "El usuario " . $usuario_name . " ha creado un nuevo usuario";
        $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
        $query = mysqli_query($db, $sqli);
        if ($query) {
            
            if ($guardar) {
                $_SESSION['guardado'] = 'Usuario registrado con exito';
                header('location: admin.php');
            }else{
                $_SESSION['alerta'] = 'error al registrar usuario';
                header('location: usuario_nuevo.php');
            }
        }

        
    }else{
        $_SESSION['alertas'] = $alertas;
        header('location: usuario_nuevo.php');
    }
}else{
    echo '<script>';
    echo 'window.location="index.php"';
     echo '</script>';
     exit;
}

?>