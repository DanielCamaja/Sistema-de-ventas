<?php 
	session_start();
	
	include "../conexion.php";
	



	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['vehiculo']) || empty($_POST['kilometraje']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}else{
            
			$nit        = $_POST['nit'];
			$nombre     = $_POST['nombre'];
			$telefono   = $_POST['telefono'];
			$mail   = $_POST['email'];
			$direccion  = $_POST['direccion']; 
            $usuario_id = $_SESSION['idUser'];
            $vehiculo   = $_POST['vehiculo'];
            $placa   = $_POST['placa'];
			$kilometraje= $_POST['kilometraje'];

			$foto   	 = $_FILES['foto'];
			$nombre_foto = $foto['name'];
			$type 		 = $foto['type'];
			$url_temp    = $foto['tmp_name'];

			$imgProducto = 'img_producto.png';

			if($nombre_foto != '')
			{
				$destino    = 'img/uploads/';
				$img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
				$imgProducto= $img_nombre.'.jpg';
				$src        = $destino.$imgProducto;
			}
			
            $result = 0;
            if (is_numeric($nit) and $nit !=0) {
            $query = mysqli_query($conection,"SELECT * FROM cliente WHERE nit = '$nit'");
            $result = mysqli_fetch_array($query);
            }

            if ($result >0) {
                $alert='<p class="msg_error">El numero de NIT ya existe.</p>';
            }else {
                $query_insert = mysqli_query($conection,"INSERT INTO cliente(nit,nombre,telefono,email,direccion,usuario_id,vehiculo,placa,kilometraje,foto)
																	VALUES('$nit','$nombre','$telefono','$mail','$direccion','$usuario_id','$vehiculo','$placa','$kilometraje','$imgProducto')");
				
				if($query_insert){
					if($nombre_foto != '')
					{
						move_uploaded_file($url_temp, $src);
					}
					$alert='<p class="msg_save">Cliente creado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al crear el cliente.</p>';
				}
            }
        }
        mysqli_close($conection);
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
    $mail->setFrom('aqui va el correo que envia', 'Mecanica');
    $mail->addAddress($_POST['email']);     // Add a recipient
    
    $mail->addReplyTo($_POST['email'],$_POST['nombre']); 
 

   
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Registro Nuevo Usuario';
    $mail->Body    = '<h1 align=center>NIT: '.$_POST['nit'].'<br>Nombre: '.$_POST['nombre'].'<br>Email: '.$_POST['email'].'<br>Telefono: '.$_POST['telefono'].'<br>Direccion: '.$_POST['direccion'].
    '<br>Vehiculo: '.$_POST['vehiculo'].'<br>Placa: '.$_POST['placa'].'<br>Kilometraje: '.$_POST['kilometraje'].'</h1>';
    $mail->AltBody = '';

    $mail->send();
    echo "<script>alert('El Registro de usuario a sido Exitoso');window.location.href='lista_clientes.php';</script>";

} catch (Exception $e) {
    echo "Error con acceso al servidor: {$mail->ErrorInfo}";
}

?>