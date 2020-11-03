<?php

session_start();
	
	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['vehiculo']) || empty($_POST['comentario']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
            //$idcliente  = $_POST['idcliente'];
			$nombre     = $_POST['nombre'];
			$telefono   = $_POST['telefono'];
			$vehiculo  = $_POST['vehiculo']; 
            $usuario_id = $_SESSION['idUser'];
			$comentario   = $_POST['comentario'];
			$servicio   = $_POST['servicio'];
            
            $result = 0;
            

            if ($result >0) {
                $alert='<p class="msg_error">El numero de NIT ya existe.</p>';
            }else {
                $query_insert = mysqli_query($conection,"INSERT INTO diagnostico(nombre,telefono,vehiculo,usuario_id,servicio,comentario)
																	VALUES('$nombre','$telefono','$vehiculo','$usuario_id','$servicio','$comentario')");
                if($query_insert){
					$alert='<p class="msg_save">Cliente creado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al crear el cliente.</p>';
				}
            }
        }
	}
?>
<?php 


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


include('envia/Exception.php');
include('envia/PHPMailer.php');
include('envia/SMTP.php');
try{

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);


    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'aqui va el correo que envia';                     // SMTP username
    $mail->Password   = 'tu password';                               // SMTP password
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('aqui va el correo que envia', 'Nuevo Diagnostico');
    $mail->addAddress($_POST['email']);     // Add a recipient
    
    $mail->addReplyTo($_POST['email'],$_POST['nombre']); 
 

   
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Registro nuevo diagnostico';
    $mail->Body    = '<h1 align=center>No. Servicio: '.$_POST['idcliente'].'<br>Nombre: '.$_POST['nombre'].'<br>Telefono: '.$_POST['telefono'].'<br>Vehiculo: '.$_POST['vehiculo'].'<br>Comentario: '.$_POST['comentario'].'<br>Servicio: '.$_POST['servicio'].'</h1>';
    $mail->AltBody = '';

    $mail->send();
    echo "<script>alert('El Registro del nuevo diagnostico exitoso');window.location.href='lista_diagnostico.php';</script>";

} catch (Exception $e) {
    echo "Error con acceso al servidor: {$mail->ErrorInfo}";
}

?>