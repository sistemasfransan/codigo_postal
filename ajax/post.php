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
	
	$select = "SELECT 1
			   FROM tab_sitios a 
			   WHERE a.indEstado = '1' AND
					 a.codSitio = '$_POST[codSitio]' ";
		
	$select = $conexion -> Consultar( $select, "i" );
	
	if( $select )
	{
		$codigo = "SELECT MAX( a.codRegistro ) as codigo
				   FROM tab_registros a 
				   WHERE codSitio = '$_POST[codSitio]' ";
		
		$codigo = $conexion -> Consultar( $codigo, "a" );
		
		if( !$codigo[codigo] ) $codigo[codigo] = 1;
		else $codigo[codigo]++;
		
		$insert = "INSERT INTO tab_registros 
					(
						codRegistro, codSitio, fecRegistro, 
						codAccion, usrRegistro
					) 
					VALUES  
					(
						'$codigo[codigo]', '$_POST[codSitio]', NOW(), 
						'$_POST[ultimo]', '$_POST[codUsuario]'
					)";
		
		//(mail( "info@alsagacompany.com", "MENSAJE", $insert );
		$conexion -> Consultar( $insert );
	}
}
?>