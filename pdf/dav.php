<?php
session_start();

include( "../lib/pdf.inc" );
include( "../lib/fn.php" );
include( "../lib/core/init_vars.php" ); 
include( "../lib/conexion.php" );
include( "declaracion.php" );

if( $_SESSION[session_user][codUsuario] )
{
	$pdf = new PDF();
	$pdf -> AliasNbPages();
	$pdf -> AddPage();
	$pdf -> Hoja1();
	$pdf -> AddPage();
	$pdf -> Hoja2();
	$pdf -> AddPage();
	$pdf -> Hoja3();
	$pdf -> AddPage();
	$pdf -> Hoja4();
	$pdf -> Output();
}
?> 
