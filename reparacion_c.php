<?php require_once 'templeat/header.php';
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alertas'] = 'Por favor introducir un usuario';
    echo '<script>';
    echo 'window.location="login_form.php"';
    echo '</script>';
}
$periodo = $_SESSION['periodos']['periodo'];

$sql = "select a.ano, a.id, s.seccion from ano a inner join seccion s on s.id = a.id_seccion where a.id  in(select id_ano from reparacion where periodo = '$periodo' )";
$query = mysqli_query($db, $sql);
?>
<style>
    main div a {
        
        padding: 15px 25px;
        border-radius: 3px;
        background-color: var(--blue-secondary);
        color: white;
    }
    #miCuadro {
  width: 200px;
  height: 200px;
  background-color: #3498db; /* Color de fondo */
  border: 2px solid #2c3e50; /* Borde sólido de 2px de ancho */
  border-radius: 10px; /* Bordes redondeados */
  padding: 20px; /* Espaciado interno */
  color: #ffffff; /* Color del texto */
  text-align: center; /* Alineación del texto al centro */
}
 
</style>
<main>
    <h2>Años con estudiantes en reparación</h2>
    <div style="display: flex; flex-direction: column; gap: 30px; ">
<?php
    
if (mysqli_num_rows($query) > 0) {
    
    while($querys = mysqli_fetch_assoc($query)){
        ?>

<a href="reparacion.php?id_ano=<?=$querys['id']?>" style="text-decoration: none; "><?=$querys['ano'] .''. $querys['seccion']?></a>


<?php 
} 
}else {
    echo '<div style="background-color: #3498db; width: 200px;  border: 2px solid #2c3e50; height: 200px; border-radius: 10px; padding: 20px; 
    color: #ffffff; 
    text-align: center; "> No hay años en reparacion</div>';
}
?>
</div>
</main>


<?php require_once 'templeat/footer.php' ?>