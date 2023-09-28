<?php
require_once 'templeat/header.php';
?>
<main>

    <div id="principal">
        
<h1>Auditoria</h1>

<?php
    //sacar las ultimas 4 entradas(Revisar y leer las funciones) 
    $sql = "select a.*, u.nombre from auditoria a inner join usuarios u on u.id = a.id_usuario order by a.fecha desc";
    $query = mysqli_query($db, $sql);
    if(!empty($query)):
        while($entrada = mysqli_fetch_assoc($query)):         
            ?>
    <article class="entrada">
        <div>
           <h2>Usuario <?=$entrada['nombre']?> </h2>
           <span class="fecha"><?=$entrada['movimiento']?></span>
           <span>
            Fecha:
               <?= substr($entrada['fecha'], 0,200). '...'  ?>
            </span>
        </div>
    </article>
    <?php 
     endwhile;
    endif; 
 ?>
</div>
</main>
<?php require_once 'templeat/footer.php';
?>