<div id="header" >
	<table cellspacing=0 cellpadding=0 border=0 width="100%" style="border-spacing: 0px;">
		<?
		if ($_SESSION[session_user] && !$_GET[popup] ):

		$empresa = "SELECT a.nitEmpresa, a.nomEmpresa, a.dirEmpresa, 
						   a.telEmpresa, b.nomCiudad, a.celEmpresa,
						   a.corEmpresa
					 FROM tab_empresa a,
						  tab_ciudades b 
					 WHERE a.ciuEmpresa = b.codCiudad ";

		$empresa = $this -> conexion -> Consultar( $empresa, "a" );
		
		$select = "SELECT a.nomSitio
					 FROM tab_sitios a
					 WHERE a.codSitio = '".$_SESSION[session_user][codSitio]."' ";

		$select = $this -> conexion -> Consultar( $select, "a" );
		
		//MENU.
		echo "<tr>";
		
		echo "<td rowspan='2' class='logoHeader' width='20%' >";
		echo "<img src='images/empresa.png' style='width:auto; height:35px; max-height:35px; vertical-align:middle' >";
		echo "</td>";
		
		echo "<td width='80%' >";
			include( "lib/menu.php");
		echo "</td>";
		
		echo "</tr>";
		?>
		<tr>
			<td>
				<div id="headerInfo" >
					<b>Empresa: </b><?=$_SESSION[session_user][nomEmpresa]?> |
					<b>Usuario: </b><?=$_SESSION[session_user][codUsuario]?> 
				</div>
			</td>
		</tr>
		<? endif; ?>
	</table>
</div>