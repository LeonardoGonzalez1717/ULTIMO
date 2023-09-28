<?php 
require_once 'templeat/header.php';
$periodo = $_SESSION['periodos']['periodo'];

    
    $sql = "SELECT ano.ano, s.seccion, ano.id FROM ano INNER JOIN seccion s on s.id = ano.id_seccion WHERE ano.id NOT IN (SELECT Pensum.id_ano FROM Pensum WHERE Pensum.cursando= '$periodo') order by ano.id";
    $resultado =  mysqli_query($db, $sql);
    
    $sqli = "select * from materia";
    $resultadoi = mysqli_query($db, $sqli);
     if (isset($_SESSION['guardado'])){
        echo '<div class="alert alert-success" role="alert">';
         echo $_SESSION['guardado']; 
      echo '</div>';
     }
?>
<h2>Creacion de pensum <?=$_SESSION['periodos']['periodo']?></h2>
    <form method="POST" action='crear_pensum.php' id="register">
        <label for="first-name">Año
        <select name="ano" id="cbx_ano" class="select">
        
             <?php 
            if(mysqli_num_rows($resultado) > 0){
                 while ($row = mysqli_fetch_assoc($resultado)):
             ?>
                <option value="<?=$row['id']?>"><?=$row['ano'].' '. $row['seccion']?></option>`
                                            
                <?php 
            endwhile;
            }else{
                echo "<option value='0'>Ya todos los años tienen pensum </option>";
            }
        ?>
             </select>
        </label>
        
        
        <input type="submit">
        </form>


<?php 
borrarErrores();
require_once 'templeat/footer.php';
?>