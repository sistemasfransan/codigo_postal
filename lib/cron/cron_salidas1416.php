<?

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

include( "../core/init_vars.php" ); 
include( "../conexion.php" ); 

date_default_timezone_set( "America/Bogota" );

$conexion = new Conexion();	

$fecha = date( "Y-m-d", mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" )) );
$fecha = date( "Y-m-d" );


$select = "SELECT a.codMovimiento, b.nomTipo, c.nomAlmacen,
				  d.refArticle, d.desArticle, a.codArticle,
				  e.nomColor, a.numTalla, f.nomTrademark
		   FROM tab_movimientos a,
				tab_tipmovimiento b,
				tab_almacenes c,
				tab_articles d,
				tab_colores e,
				tab_trademark f
		   WHERE a.codTipo = b.codTipo AND
				 a.codAlmacen = c.codAlmacen AND
				 DATE( a.fecCreacion ) = '$fecha' AND
				 a.codTipo = '2' AND
				 a.codArticle = d.codArticle AND
				 a.codColor = e.codColor AND
				 d.codTrademark = f.codTrademark AND
				 a.codSalida = '1'
		   ORDER BY c.nomAlmacen ";

$select = $conexion -> Consultar( $select, "a", TRUE );

if( $select )
{
	$asunto = "Estado de Inventario";	
		
	$headers  = "From: Stock-In <no-reply@alsagacompany.com>\r\n";			
	$headers .= "Return-Path: Stock-In <no-reply@alsagacompany.com>\r\n"; 
	$headers .= "Organization: Elements Applications\r\n"; 
	$headers .= "X-Sender: Stock-In <no-reply@alsagacompany.com>\n";
	$headers .= "Reply-To: Stock-In <no-reply@alsagacompany.com>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";		
	$headers .= "X-Mailer: PHP\n";
	
	$html  = "<div style='font-family:verdana; font-size:14px; padding:10px;' >";		
	
	$html .= "<h1>$asunto</h1>";
	
	$html .= "Estas fueron las salidas del d&iacute;a <b>$fecha</b>.<br><br>";
	
	$styleHD = " style='border:1px solid #ddd; padding:10px; font-size:12px; font-family:verdana; background-color:#1DB202 ; color:#fff' ";
	
	$html .= "<table border=0 width='100%'>";
	$html .= "<tr>";
	$html .= "<th$styleHD colspan=7 >Se encontraron ".sizeof( $select )." salidas.</th>";
	$html .= "</tr>";
		
	$html .= "<tr>";
	$html .= "<th$styleHD >Codigo Salida</th>";
	$html .= "<th$styleHD >Almac&eacute;n</th>";
	$html .= "<th$styleHD >Marca</th>";
	$html .= "<th$styleHD >Cod. Art&iacute;culo</th>";
	$html .= "<th$styleHD >Art&iacute;culo</th>";
	$html .= "<th$styleHD >Color</th>";
	$html .= "<th$styleHD >Talla</th>";
	$html .= "</tr>";
	
	$styleTD = " style='border:1px solid #ddd; padding:3px 10px; font-size:12px; font-family:verdana; background-color:#fff; color:#000; vertical-align:top' ";
	
	foreach( $select as $row )
	{
		$html .= "<tr>";
		$html .= "<td $styleTD align=center nowrap><b>$row[codMovimiento]</b></td>";
		$html .= "<td $styleTD align=left nowrap>$row[nomAlmacen]</td>";
		$html .= "<td $styleTD align=left nowrap>$row[nomTrademark]</td>";
		$html .= "<td $styleTD align=left nowrap>$row[codArticle]</td>";
		$html .= "<td $styleTD align=left nowrap><b>$row[refArticle]</b> $row[desArticle]</td>";
		$html .= "<td $styleTD align=left nowrap>$row[nomColor]</td>";
		$html .= "<td $styleTD align=center nowrap><b>$row[numTalla]</b></td>";
		$html .= "</tr>";
	}
	
	$html .= "<tr>";
	$html .= "<th$styleHD colspan=7 >Se encontraron ".sizeof( $select )." salidas.</th>";
	$html .= "</tr>";
	
	$html .= "</table>";
	
	
	$select = "SELECT a.codArticle, c.nomTrademark, b.refArticle, 
					  b.desArticle, SUM( a.numExistencia ) AS existencia
			   FROM tab_existencias a,
					tab_articles b,
					tab_trademark c
			   WHERE a.codArticle = b.codArticle AND
					 b.codTrademark = c.codTrademark 
			   GROUP BY a.codArticle
			   HAVING  SUM( a.numExistencia ) < '5'
			   ORDER BY c.nomTrademark ";

	$select = $conexion -> Consultar( $select, "a", TRUE );
		
	$html .= "<br>";
	$html .= "<h1>Art&iacute;culos con poca existencia.</h1>";
	$html .= "<br>";
	
	if( $select )
	{		
		$html .= "<table border=0 width='100%'>";
		
		$html .= "<tr>";
		$html .= "<th$styleHD colspan=5 >Se encontraron ".sizeof( $select )." art&iacute;culos con poca existencia.</th>";
		$html .= "</tr>";
		
		$html .= "<tr>";
		$html .= "<th$styleHD >Cod. Art&iacute;culo</th>";
		$html .= "<th$styleHD >Marca</th>";
		$html .= "<th$styleHD >Ref. Art&iacute;culo</th>";
		$html .= "<th$styleHD >Nombre Art&iacute;culo</th>";
		$html .= "<th$styleHD >Existencia</th>";
		$html .= "</tr>";
	
		foreach( $select as $row )
		{
			$html .= "<tr>";
			$html .= "<td $styleTD align=center nowrap><b>$row[codArticle]</b></td>";
			$html .= "<td $styleTD align=left nowrap>$row[nomTrademark]</td>";
			$html .= "<td $styleTD align=left nowrap>$row[refArticle]</td>";
			$html .= "<td $styleTD align=left nowrap>$row[desArticle]</td>";
			$html .= "<td $styleTD align=center nowrap><b>$row[existencia]</b></td>";
			$html .= "</tr>";
		}
		
		$html .= "<tr>";
		$html .= "<th$styleHD colspan=5 >Se encontraron ".sizeof( $select )." art&iacute;culos con poca existencia.</th>";
		$html .= "</tr>";
		
		$html .= "</table>";
	}
	
	$html .= "<br>";
	$html .= "Cordialmente,<br><br>";
	$html .= "<img src='http://jovo90.com/stockin/images/empresa.png' style='margin:10px;'><br>";		
	$html .= "<b>Inventario La Moda del Calzado</b><br>";		
	$html .= "</div>";	
	
	mail( "rendon321@hotmail.com, johnrendon8a@hotmail.com", $asunto, $html, $headers );
	
}
?>