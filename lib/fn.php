<?php
function getSession()
{
	return $_SESSION[SESSION][session_user];
}

function getUsuario()
{
	return $_SESSION[SESSION][session_user][codUsuario];
}

function getDia( $fecha )
{
	$date = explode( "-", $fecha );
	
	$nom_dia = date( "w", mktime( 0,0,0, $date[1], $date[2], $date[0] ) );
	
	switch( $nom_dia )
	{
		case 1: return "Lunes";
		case 2: return "Martes";
		case 3: return "Miercoles";
		case 4: return "Jueves";
		case 5: return "Viernes";
		case 6: return "Sabado";
		default: return "Domingo";
	}
}

function getMes( $codMes )
{
	switch( $codMes )
	{
		case 1: return "Enero"; break;
		case 2: return "Febrero"; break;
		case 3: return "Marzo"; break;
		case 4: return "Abril"; break;
		case 5: return "Mayo"; break;
		case 6: return "Junio"; break;
		case 7: return "Julio"; break;
		case 8: return "Agosto"; break;
		case 9: return "Septiembre"; break;
		case 10: return "Octubre"; break;
		case 11: return "Noviembre"; break;
		default: return "Diciembre"; break;
	}
}

function getFecha( $fecha )
{
	$date = explode( "-", $fecha );
	
	$dia = $date[2];
	$ano = $date[0];
	$fecha = getDia( $fecha )." $dia de ".getMes( $date[1] )." del $ano";
	
	return $fecha;
}

function getDigito( $nit )
{
	$nit = explode( "-", $nit );
	$nit = $nit[0];
		
	$div = array( 71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3 );
	$suma = 0;

	$resto = 15-strlen( $nit );

	for( $i = 0; $i < $resto; $i++ )
	{
		$nit = "0$nit";
	}

	for( $i = 0; $i < 15; $i++ )
	{
		$suma += $nit[$i] * $div[$i];
	}
	$mod = $suma % 11;
	if( $mod == 1 || $mod == 0 )
		return $mod;
	else
		return ( 11 - $mod );
}

function Clear( $data )
{
	$clean = $data;
	$clean = str_replace( "'", "", $clean );
	$clean = str_replace( '*', "", $clean );
	$clean = str_replace( "/", "", $clean );
	$clean = str_replace( '"', "", $clean );
	$clean = str_replace( '<', "", $clean );
	$clean = str_replace( '>', "", $clean );	
	return $clean;
}

function Prueba( $data )
{
	echo "<pre>";
	print_r( $data );
	echo "</pre>";
} 

function MessageOk( $message )
{
	echo "<div class='ui-state-default ui-corner-all' style='margin:auto; width:50%; padding:10px'  >";
	echo "<span class='ui-icon ui-icon-check' style='margin:auto' ></span>";
	echo "$message";
	echo "</div>";
	echo "<br/>";
}

function MessageAlert( $message )
{
	echo "<div class='ui-state-highlight ui-corner-all' style='margin:10px; width:50%; padding:10px'  >";
	echo '<span class="ui-icon ui-icon-info" style=" margin-right: .3em;"></span>';
	echo "$message";
	echo "</div>";
}

function MessageError( $message )
{
	echo "<div class='ui-widget' >";
	echo "<div class='ui-state-error ui-corner-all'  style='padding:10px; width:50%;' >";
	echo "<span class='ui-icon ui-icon-alert'></span>";
	echo "$message";
	echo "</div>";
	echo "</div>";
}



?>