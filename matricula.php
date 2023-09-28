<?php include_once 'templeat/header.php'; 
$ano = conseguirAnos($db);

?>
<h2>Matriculacion <?=$_SESSION['periodos']['periodo']?></h2>
<form method="POST" action='matricula_view.php' id="register">
    <label for="first-name">Año
        <select name="ano" id="cbx_ano" class="select">
            
            <?php while ($anos = mysqli_fetch_assoc($ano)) :?>
             
                <option value="<?=$anos['id']?>"><?=$anos['ano'].' '. $anos['seccion']?></option>`
                                            
                <?php endwhile; ?>
             </select>
        </label>
        
        
        <input type="submit">
        </form>
  
<?php 
include_once 'templeat/footer.php'; ?>
  