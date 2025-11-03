<?
$saludo = "Buenos d&iacute;as";
$hora = date( "H" );
if( $hora >= 12 && $hora < 18 ) $saludo = "Buenas tardes";
elseif( $hora >= 18 ) $saludo = "Buenas noches";
?>

<div align='center' >
<h2 style='text-align:left; color:#777; '>
	<?=$saludo?>, <?=getFecha( date("Y-m-d") )?>
</h2>
<table border=0 width="100%" >
<tr>
	<td align=center >
		<table border=0 background="images/home.jpg" width="427" height="404" >
			<tr>
				<td align=center height="130" valign=bottom class="mes_cal" >
				<?
				switch( date( "m" ) )
				{
					case "1": echo "Enero"; break;
					case "2": echo "Febrero"; break;
					case "3": echo "Marzo"; break;
					case "4": echo "Abril"; break;
					case "5": echo "Mayo"; break;
					case "6": echo "Junio"; break;
					case "7": echo "Julio"; break;
					case "8": echo "Agosto"; break;
					case "9": echo "Septiembre"; break;
					case "10": echo "Octubre"; break;
					case "11": echo "Noviembre"; break;
					case "12": echo "Diciembre"; break;
				}
				?>
				</td>
			</tr>
			<tr>
				<td valign=top align=center class="dia_cal" >
				<? echo date( "d" ); ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
</div>
