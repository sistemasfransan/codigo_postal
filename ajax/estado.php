<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

$codUsuario = $_SESSION[session_user][codUsuario];

if( $codUsuario )
{
	include( "../lib/core/init_vars.php" );
	include( "../lib/conexion.php" );
	include( "../lib/form.php" );
	
	$conexion = new Conexion();
	
	$codSitio = $_SESSION[session_user][codSitio];
			
	$insert = "UPDATE tab_sitios 
			   SET indEstado = '$_POST[estado]',
				   usrEdicion = '$codUsuario',
				   fecEdicion = NOW() 
			   WHERE codSitio = '$codSitio' ";
	
	$conexion -> Consultar( $insert );
	
	if( !$_POST[estado] )
	{
		echo "<b style='color:#900' >Contador Inactivo</b>";
	}
	else
	{
		echo "<b style='color:#2DB200' >Contador Activo</b>";
	}
}
?>