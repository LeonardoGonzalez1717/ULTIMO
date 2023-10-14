<?php session_start();
require_once 'helpers/funciones.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_style/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css"> 
    

    <title>Inicio de sesion La Candelaria</title>
</head>
<body>

    
    
<div class="form_container">
       <?php if (isset($_SESSION['alerta'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['alerta']?>
        </div>
      <?php elseif(isset($_SESSION['guardado'])): ?>
      <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['guardado']?>
        </div>
        <?php endif; ?>
   <form action="password_recovery.php" method="post">
    <h1>Recuperación de contraseña</h1>
            <div class="group">
            <input  type="text" class="input" name="email">
            <span class="highlight"></span>
            <span class="bar">

                <?php echo isset($_SESSION['alertas']) ? mostrarErrores($_SESSION['alertas'], 'nombre'): '';?>
            </span>
            <label>Email del usuario a recuperar</label>
            </div>
            
            <input type="submit" value="Cambiar contraseña">
            <a href="login_view.php?gmail=1">inicio de sesion</a>
        </form>
            </div>
           
            
  
</body>
</html>
<?php borrarErrores(); ?>