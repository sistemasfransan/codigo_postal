<?
require_once("class.phpmailer.php");
require_once("core/init_vars.php"); 
require_once("conexion.php");

class Mail
{
	function Mail( $cuerpo, $asunto, $correo, $nombre, $archivo, $nom_archivo )
	{
		$this -> conexion = new Conexion();
		
		$empresa = "SELECT a.nitEmpresa, a.nomEmpresa, a.dirEmpresa, 
					   a.telEmpresa, b.nomCiudad, a.celEmpresa,
					   a.corEmpresa, a.gerEmpresa
				 FROM tab_empresa a,
					  tab_ciudades b 
				 WHERE a.ciuEmpresa = b.codCiudad ";
	
		$empresa = $this -> conexion -> Consultar( $empresa, "a" );
		
		$mail = new PHPMailer();
		
		$mail->From = $empresa[corEmpresa];
		$mail->FromName = $empresa[nomEmpresa];
		$mail->Subject = $asunto;
		$mail->AddAttachment( $archivo, $nom_archivo );
		$mail->AddBCC( $empresa[corEmpresa] );
		$mail->Body = $cuerpo;
		$mail->IsHTML(true);
		$mail->AddAddress( $correo, $nombre );
		$mail->Send();
	}
}
?>