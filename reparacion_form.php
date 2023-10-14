<?php 
require_once 'model/conexion.php';
session_start();
if(isset($_POST) && !empty($_POST)) {
$periodo = $_SESSION['periodos']['id'];
$periodo_new = $periodo + 1;
$id_ano = $_POST['ano'];
$id_ano_new = $id_ano + 3;
$cuenta = $_POST['cuenta'];
$id_alumno = $_POST['alumno'];
$slq_reparacion = "select * from alumno where id = $id_alumno";
$query_reparacion = mysqli_query($db, $slq_reparacion);
$querys_reparacion = mysqli_fetch_assoc($query_reparacion);



$evaluation = "SELECT sum(cantidad_evaluacion) as evaluaciones from reparacion WHERE id_ano = $id_ano";
$query_evaluation = mysqli_query($db,$evaluation);
$all_evaluations = mysqli_fetch_assoc($query_evaluation);
$all_evaluation = $all_evaluations['evaluaciones'];





$raspado = array();
for ($i=1; $i<=$cuenta; $i++) { 
    $count_materia = array();
    $nota = $_POST['nota'.$i];
    $id_materia = $_POST['id_materia'];
    $id_reparacion = $_POST['id_reparacion'];

   
        $sql_ = "insert into reparacion_notas values(null, $id_reparacion, $nota)";
        $query_update = mysqli_query($db, $sql_);
        if ($query_update) {
            $query_update = true;
        }else{
            echo 'error';
        }

 if ($nota >= 10) {
    $notas = true;
}else{
    $raspado[] = $nota;
}

}
$sql_cuenta = "SELECT COUNT(notas) as notas_count from reparacion_notas r inner join reparacion re on re.id = r.id_reparacion where re.id_alumno = $id_alumno";
$query_cuenta = mysqli_query($db, $sql_cuenta);
$querys_cuenta = mysqli_fetch_assoc($query_cuenta);
$cuneta = $querys_cuenta['notas_count'];




    $verify = "select * from cursando where id_alumno = $id_alumno and id_periodo = $periodo_new";
    $query = mysqli_query($db, $verify);
    if (mysqli_num_rows($query) > 0) {
        if ($cuneta == $all_evaluation) {

            $_SESSION['guardado'] = 'Estudiante ya esta estudianto el siguiente periodo';
        }
    }else{
       
      
    if (count($raspado) == 0) {
        
        if ($cuneta == $all_evaluation) {
    
                    $sql = "insert into cursando values(null, $id_alumno, $id_ano_new, '$periodo_new')";
                    $guardar = mysqli_query($db, $sql);
                
    
                if (isset($_SESSION['usuario_admin'])) {
                    $usuario_name = $_SESSION['usuario_admin']['nombre'];
                    $usuario_id = $_SESSION['usuario_admin']['id'];
                }
        
                $movimiento = "El usuario " . $usuario_name . " ha aprobado al estudiante ".$querys_reparacion['nombre'].' '.$querys_reparacion['apellido'];
                $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
                $query = mysqli_query($db, $sqli);
                if ($query) {
                    
                    if ($guardar) {
                        $_SESSION['guardado'] = 'El estudiante ha aprobado con exito, estudiara el siguiente año';

                    }else{
                        $_SESSION['guardado'] = 'Periodo no encontrado';
                        
                    }
                }
            
        }
    }else{

    
        if (mysqli_num_rows($query) > 0) {
            $estudiante = true;
        }else {
            
            
            
            $sql = "insert into cursando values(null, $id_alumno, $id_ano, '$periodo_new')";
            $guardar = mysqli_query($db, $sql);
            
    
            if (isset($_SESSION['usuario_admin'])) {
                $usuario_name = $_SESSION['usuario_admin']['nombre'];
                $usuario_id = $_SESSION['usuario_admin']['id'];
            }
        
            $movimiento = "El usuario " . $usuario_name . " ha mandado al estudiante " . $querys_reparacion['nombre'].' '.$querys_reparacion['apellido'] . " repetir el periodo";
            $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
            $query = mysqli_query($db, $sqli);
            if ($guardar) {
                $_SESSION['guardado'] = 'El estudiante NO ha aprobado todas sus evaluaciones con exito, repetira el año';
                
        }
        
        
    }
    }
   
        
}

}else{
    $_SESSION['guardado'] = 'campos no pueden estar vacios';
}

header('location: reparacion_view.php?alumno='.$id_alumno); 


?>