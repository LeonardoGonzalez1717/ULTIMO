<?php
require_once 'templeat/header.php'; 
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alerta'] = 'Por favor introducir un usuario';
        echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}
if (isset($_POST['ano'])) {
    
    $ano = $_POST['ano'];
}elseif (isset($_GET['ano'])) {
        $ano = $_GET['ano'];
}else{
    echo '<script>';
    echo 'window.location="index.php"';
     echo '</script>';
     exit;
}
if (isset($ano)) {
    
    $sql = "select a.id,a.ano, s.seccion from ano a left join seccion s on s.id = a.id_seccion where a.id = $ano";
    $guardar = mysqli_query($db, $sql);
    $ano = mysqli_fetch_assoc($guardar);
}
?>
<?php if(isset($_SESSION['guardado'])): ?>
    <div class="alert alert-success" role="alert">
  <?php echo $_SESSION['guardado'] ?>
</div>
<?php elseif(isset($_SESSION['alerta'])): ?>
    <div class="alert alert-danger" role="alert">
  <?php echo $_SESSION['alerta'] ?>
</div>
<?php endif; ?>
<h2><span style="color:brown"><?= $ano['ano'].' '.$ano['seccion'].' // '. $_SESSION['periodos']['periodo']?></span></h2>
<form action="pensum_view.php" id="register" method="post" >
<?php
    $sqli = "select * from materia";
    $resultadoi = mysqli_query($db, $sqli);
    ?>

        <h4>Materias para la creacion del pensum</h4>
           
            <div class="register_pensum" style="height: 400px; overflow-y: scroll;">
                
                <?php 
                if(!empty($resultadoi)){
                    $i = 0;
                     while ($rowi = mysqli_fetch_assoc($resultadoi)){
                         $i++;
                    
                         echo '<label style="display: flex;">';
                         echo '<p style="margin: 0;">'.$rowi['materia'].'</p>';
                    echo '<input type="checkbox"  name="" value="'.$rowi['id'].'" class="checkbox">';
                    echo '</label>';
                }     
            }
            ?>
                
            </div>
                <input type="hidden" name="ano" value="<?=$ano['id']?>">
                <input type="submit" value="Registrar">
    </form>
<?php require_once 'templeat/footer.php'; 
borrarErrores();
?>


<script>
    const checkBoxBtn = document.querySelectorAll('.checkbox')

    let contador = 1
    checkBoxBtn.forEach(btns => {
        btns.addEventListener('change', () => {
            // const nameCheck = btns.previousElementSibling.textContent
            const nameCheck = `materia${contador}`

            if(btns.name !== "") {
                btns.name = ''
                contador-- 
            }else{
                btns.name = nameCheck
                contador++
            }
        })
    });
</script>