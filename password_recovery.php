<?php
require 'php_mailer/Exception.php';
require 'php_mailer/PHPMailer.php';
require 'php_mailer/SMTP.php';
require_once 'model/conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = trim($_POST['email']);
    echo $email;
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_escapado = mysqli_escape_string($db, $email);
        $sql = "select * from usuarios where email = '$email_escapado'";
        $query = mysqli_query($db, $sql);
        $querys = mysqli_fetch_assoc($query);
        $nombre = $querys['nombre'];
        $usuario_id = $querys['id'];
        if (mysqli_num_rows($query) > 0) {
            $logitudPass = 5;   
            $miPassword  = substr( md5(microtime()), 1, $logitudPass);
            $clave = $miPassword;
            $clave_encrip = base64_encode($clave);


            $updateClave = "UPDATE usuarios SET password= '$clave_encrip' WHERE email= '$email_escapado'";
            $queryResult    = mysqli_query($db,$updateClave);
            
    
            $movimiento = "Se ha cambiado la contraseña al usuario " . $nombre;
            $sqli = "insert into auditoria values(null, '$movimiento', $usuario_id, now())";
            $queryi = mysqli_query($db, $sqli);
    
            if ($queryi) {

                if ($queryResult) {
                    $update = true;
                }else{
                    $update = false;
                }
            }


            $destinatario = $email_escapado; 
            $mail = new PHPMailer(true);
            
            try {
                
                $mail->isSMTP();                                           
                $mail->Host       = 'smtp-mail.outlook.com';               
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'leitogonza1717@outlook.com';                     
                $mail->Password   = 'leito17.17';                               
                $mail->SMTPSecure = 'tls';            
                $mail->Port       = 587;                
            
                //Recipients
                $mail->setFrom('leitogonza1717@outlook.com', 'Recuperacion de contraseñas');
                $mail->addAddress($destinatario);     
            
                //Content
                $mail->isHTML(true);                                  
                $mail->Subject = 'Recuperacion de contraseña';
                $mail->Body    = '<h1 style="color = blue;" >Querido usuario</h1> <p>su contrasena fue cambiada a <strong>'.$clave.'</strong> esta es una contraseña temporal, agradecemos que una vez entre en el sistema, actualice la contraseña por una mas segura</p>

                ';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
                $mail->send();
                $_SESSION['guardado'] = 'Contraseña actualizada. Por favor revisar el correo';
                
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }else{
            $_SESSION['alerta'] = 'Correo no existe';
        }
    }else {
        $_SESSION['alerta'] = 'Correo invalido';
    }
    
}else{
    
    $_SESSION['alerta'] = 'El campo no debe estar vacio';
}
header('location: p_recovery.php');   
