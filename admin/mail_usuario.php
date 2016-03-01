<?
include_once("../includes/DB_Conectar.php");
include_once("../includes/phpmailer/class.phpmailer.php");

$id_usuario = $_GET["id_user"];
$accion = $_GET["accion"];

$aux = $conn->execute("SELECT * FROM usuarios WHERE id = '".$id_usuario."'");

if(!$aux->eof)
{

	$nombre = $aux->field("nombre");
	$apellido = $aux->field("apellido");
	$usuario = $aux->field("usuario");
	$email = $aux->field("email");
	$password = $aux->field("password");

	if($accion == "A")
	{
		$body = "
			".$nombre." ".$apellido." ha sido acreditado en el sitio  Sala de prensa, www.prensa.medios.gov.ar \r\n
			Usuario: ".$usuario." \r\n
			Clave: ".$password." \r\n
			Ante cualquier inconveniente contactar via mail a editores@medios.gov.ar \r\n
			Gracias. \r\n
		";
	}
	else
	{
		$body = "
			".$nombre." ".$apellido." su pedido de acreditación en Sala de prensa, www.prensa.medios.gov.ar no ha sido aprobado. \r\n
			Contacte con los administradores via mail a editores@medios.gov.ar \r\n
			Gracias por su contacto. \r\n
		";
	}
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = $mailer_smtp;
	$mail->From = "editores@medios.gov.ar";
	$mail->FromName = "Sala de Prensa - Medios";
	$mail->AddAddress($email);
	$mail->WordWrap = 70;
	$mail->IsHTML(false);
	$mail->Subject = "Solicitud de usuario www.prensa.medios.gov.ar";
	$mail->Body    = $body;
	$mail->Send();

}

?>
<script>
window.close();
</script>